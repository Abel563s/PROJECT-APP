<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Project;
use App\Support\ProjectOrganizationFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Display a listing of in-progress projects for issue management.
     */
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:all,in progress,completed',
            'issue_status' => 'nullable|in:Raised,Open,Closed',
            'priority' => 'nullable|in:Overdue,High,Medium,Low',
        ]);

        $today = Carbon::today();
        $priorityScope = function ($query) {
            $query->where(function ($statusQuery) {
                $statusQuery->whereNull('status')->orWhere('status', '!=', 'Closed');
            })->whereNotNull('planned_resolution_date');
        };

        $query = Project::query()
            ->withCount('issues as total_issues')
            ->withCount(['issues as raised_issues' => fn ($issues) => $issues->where('status', 'Raised')])
            ->withCount(['issues as open_issues' => fn ($issues) => $issues->where('status', 'Open')])
            ->withCount(['issues as closed_issues' => fn ($issues) => $issues->where('status', 'Closed')])
            ->withCount(['issues as overdue_issues' => function ($issues) use ($today, $priorityScope) {
                $priorityScope($issues);
                $issues->whereDate('planned_resolution_date', '<', $today);
            }])
            ->withCount(['issues as high_priority_issues' => function ($issues) use ($today, $priorityScope) {
                $priorityScope($issues);
                $issues->whereDate('planned_resolution_date', '>=', $today)
                    ->whereDate('planned_resolution_date', '<', $today->copy()->addDays(7));
            }])
            ->withCount(['issues as medium_priority_issues' => function ($issues) use ($today, $priorityScope) {
                $priorityScope($issues);
                $issues->whereDate('planned_resolution_date', '>=', $today->copy()->addDays(7))
                    ->whereDate('planned_resolution_date', '<', $today->copy()->addDays(14));
            }])
            ->withCount(['issues as low_priority_issues' => function ($issues) use ($today, $priorityScope) {
                $priorityScope($issues);
                $issues->whereDate('planned_resolution_date', '>=', $today->copy()->addDays(14));
            }])
            ->withCount(['issues as high_impact_issues' => fn ($issues) => $issues->where('impact', 'High')])
            ->withCount(['issues as medium_impact_issues' => fn ($issues) => $issues->where('impact', 'Medium')])
            ->withCount(['issues as low_impact_issues' => fn ($issues) => $issues->where('impact', 'Low')]);
        ProjectOrganizationFilters::apply($query, $request, true);

        if ($request->user()->isUser()) {
            $query->whereHas('coordinators', fn ($coordinators) => $coordinators->whereKey($request->user()->id));
        }

        $projectStatus = $request->input('status', 'in progress');
        if ($projectStatus !== 'all') {
            $query->where('status', $projectStatus);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhere('custom_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('issue_status')) {
            $query->whereHas('issues', function ($q) use ($request) {
                $q->where('status', $request->issue_status);
            });
        }

        if ($request->filled('priority')) {
            $priority = $request->priority;
            $query->whereHas('issues', function ($issues) use ($priority, $today, $priorityScope) {
                $priorityScope($issues);
                match ($priority) {
                    'Overdue' => $issues->whereDate('planned_resolution_date', '<', $today),
                    'High' => $issues->whereDate('planned_resolution_date', '>=', $today)
                        ->whereDate('planned_resolution_date', '<', $today->copy()->addDays(7)),
                    'Medium' => $issues->whereDate('planned_resolution_date', '>=', $today->copy()->addDays(7))
                        ->whereDate('planned_resolution_date', '<', $today->copy()->addDays(14)),
                    'Low' => $issues->whereDate('planned_resolution_date', '>=', $today->copy()->addDays(14)),
                };
            });
        }

        if ($request->filled('impact')) {
            $impact = $request->impact;
            $query->whereHas('issues', function ($issues) use ($impact) {
                $issues->where('impact', $impact);
            });
        }

        $projects = $query->orderByDesc('baseline_start_date')->paginate(15)->withQueryString();

        return view('admin.projects.issues.index', array_merge(
            compact('projects'),
            ProjectOrganizationFilters::options($request)
        ));
    }

    /**
     * Manage issues for a specific project.
     */
    public function manage(Request $request, Project $project)
    {
        abort_unless($request->user()->isAdmin() || $request->user()->isSuperadmin() || $request->user()->canManageProject($project), 403);

        $request->validate([
            'issue_owner' => 'nullable|string|max:255',
            'issue_search' => 'nullable|string|max:255',
            'impact' => 'nullable|in:Low,Medium,High',
            'status' => 'nullable|in:Raised,Open,Closed',
            'priority' => 'nullable|in:Done,Overdue,High,Medium,Low,Unscheduled',
        ]);

        $canManageProject = auth()->user()->canManageProject($project);
        $issueQuery = Issue::where('project_id', $project->id);

        if ($request->filled('issue_owner')) {
            $issueQuery->where('issue_type', $request->query('issue_owner'));
        }

        if ($request->filled('issue_search')) {
            $issueQuery->where('issue', 'like', '%'.$request->query('issue_search').'%');
        }

        if ($request->filled('impact')) {
            $issueQuery->where('impact', $request->query('impact'));
        }

        if ($request->filled('status')) {
            $issueQuery->where('status', $request->query('status'));
        }

        if ($request->filled('priority')) {
            $priority = $request->query('priority');
            $today = Carbon::today();
            $notClosed = fn ($query) => $query->where(function ($statusQuery) {
                $statusQuery->whereNull('status')->orWhere('status', '!=', 'Closed');
            });

            if ($priority === 'Done') {
                $issueQuery->where('status', 'Closed');
            } elseif ($priority === 'Unscheduled') {
                $issueQuery->whereNull('planned_resolution_date')->where($notClosed);
            } else {
                $issueQuery->where($notClosed)->whereNotNull('planned_resolution_date');
                match ($priority) {
                    'Overdue' => $issueQuery->whereDate('planned_resolution_date', '<', $today),
                    'High' => $issueQuery->whereDate('planned_resolution_date', '>=', $today)
                        ->whereDate('planned_resolution_date', '<', $today->copy()->addDays(7)),
                    'Medium' => $issueQuery->whereDate('planned_resolution_date', '>=', $today->copy()->addDays(7))
                        ->whereDate('planned_resolution_date', '<', $today->copy()->addDays(14)),
                    'Low' => $issueQuery->whereDate('planned_resolution_date', '>=', $today->copy()->addDays(14)),
                    default => null,
                };
            }
        }

        $issues = $issueQuery
            ->orderByDesc('raised_date')
            ->paginate(15)
            ->withQueryString();
        $issueOwners = Issue::where('project_id', $project->id)
            ->whereNotNull('issue_type')
            ->where('issue_type', '!=', '')
            ->distinct()
            ->orderBy('issue_type')
            ->pluck('issue_type');

        $projectIssues = Issue::where('project_id', $project->id);
        $today = Carbon::today();
        $notClosed = fn ($query) => $query->where(function ($statusQuery) {
            $statusQuery->whereNull('status')->orWhere('status', '!=', 'Closed');
        });
        $stats = [
            'total' => (clone $projectIssues)->count(),
            'raised' => (clone $projectIssues)->where('status', 'Raised')->count(),
            'open' => (clone $projectIssues)->where('status', 'Open')->count(),
            'closed' => (clone $projectIssues)->where('status', 'Closed')->count(),
            'done' => (clone $projectIssues)->where('status', 'Closed')->count(),
            'overdue' => (clone $projectIssues)->where($notClosed)->whereNotNull('planned_resolution_date')
                ->whereDate('planned_resolution_date', '<', $today)->count(),
            'high' => (clone $projectIssues)->where($notClosed)->whereNotNull('planned_resolution_date')
                ->whereDate('planned_resolution_date', '>=', $today)
                ->whereDate('planned_resolution_date', '<', $today->copy()->addDays(7))->count(),
            'medium' => (clone $projectIssues)->where($notClosed)->whereNotNull('planned_resolution_date')
                ->whereDate('planned_resolution_date', '>=', $today->copy()->addDays(7))
                ->whereDate('planned_resolution_date', '<', $today->copy()->addDays(14))->count(),
            'low' => (clone $projectIssues)->where($notClosed)->whereNotNull('planned_resolution_date')
                ->whereDate('planned_resolution_date', '>=', $today->copy()->addDays(14))->count(),
        ];

        return view('admin.projects.issues.manage', compact('project', 'issues', 'stats', 'canManageProject', 'issueOwners'));
    }

    /**
     * Show the form for creating a new issue (modal partial).
     */
    public function create(Project $project)
    {
        abort_unless(auth()->user()->canManageProject($project), 403);

        return view('admin.projects.issues.create-modal', compact('project'))->render();
    }

    /**
     * Store a newly created issue in storage.
     */
    public function store(Request $request, Project $project)
    {
        abort_unless(auth()->user()->canManageProject($project), 403);

        $validated = $request->validate([
            'issue_type' => 'nullable|string|max:255',
            'issue' => 'required|string|max:255',
            'description' => 'nullable|string',
            'area_affected' => 'nullable|string',
            'impact' => 'nullable|in:Low,Medium,High',
            'date' => 'required|date',
            'raised_date' => 'nullable|date',
            'raised_by' => 'nullable|string|max:255',
            'action_plan' => 'nullable|string',
            'planned_resolution_date' => 'nullable|date',
            'action_by' => 'nullable|string|max:255',
            'issue_closed_date' => 'nullable|date',
            'remark' => 'nullable|string',
        ]);

        $validated['project_id'] = $project->id;
        $validated['project_code'] = $project->project_code;
        $validated['sector'] = $project->consultancy_sector;
        $validated['last_updated_date'] = now();
        $validated['created_by'] = $request->user()->id;

        Issue::create($validated);

        return back()->with('success', 'Issue created successfully.');
    }

    /**
     * Display the specified issue (for modal).
     */
    public function show(Issue $issue)
    {
        if (request()->has('partial')) {
            return view('admin.projects.issues.view-modal', compact('issue'))->render();
        }

        return redirect()->route('admin.projects.issues.index');
    }

    /**
     * Show the form for editing the specified issue (modal partial).
     */
    public function edit(Issue $issue)
    {
        $this->authorizeIssueMutation($issue);

        if (request()->has('partial')) {
            $projectsQuery = Project::orderBy('project_name');
            if (auth()->user()->isUser()) {
                $projectsQuery->whereKey($issue->project_id);
            }
            $projects = $projectsQuery->get(['id', 'project_code', 'project_name', 'project_type', 'consultancy_sector']);

            return view('admin.projects.issues.edit-modal', compact('issue', 'projects'))->render();
        }

        return redirect()->route('admin.projects.issues.index');
    }

    /**
     * Update the specified issue in storage.
     */
    public function update(Request $request, Issue $issue)
    {
        $this->authorizeIssueMutation($issue);

        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'sector' => 'nullable|string|max:255',
            'project_code' => 'nullable|string|max:255',
            'issue_type' => 'nullable|string|max:255',
            'issue' => 'required|string|max:255',
            'description' => 'nullable|string',
            'area_affected' => 'nullable|string',
            'impact' => 'nullable|in:Low,Medium,High',
            'raised_date' => 'nullable|date',
            'raised_by' => 'nullable|string|max:255',
            'action_plan' => 'nullable|string',
            'planned_resolution_date' => 'nullable|date',
            'action_by' => 'nullable|string|max:255',
            'issue_closed_date' => 'nullable|date',
            'remark' => 'nullable|string',
        ]);

        if ($request->user()->isUser()) {
            unset($validated['project_id'], $validated['sector'], $validated['project_code']);
        }

        $validated['last_updated_date'] = now();

        $issue->update($validated);

        return back()->with('success', 'Issue updated successfully.');
    }

    /**
     * Display issue history across all projects.
     */
    public function history(Request $request)
    {
        $request->validate([
            'issue_owner' => 'nullable|string|max:255',
            'priority' => 'nullable|in:Done,Overdue,High,Medium,Low,Unscheduled',
        ]);

        $query = Issue::query()->with('project');
        ProjectOrganizationFilters::apply($query, $request);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('issue', 'like', "%{$search}%")
                    ->orWhere('issue_type', 'like', "%{$search}%")
                    ->orWhere('raised_by', 'like', "%{$search}%")
                    ->orWhere('action_by', 'like', "%{$search}%")
                    ->orWhereHas('project', function ($q2) use ($search) {
                        $q2->where('project_name', 'like', "%{$search}%")
                            ->orWhere('project_code', 'like', "%{$search}%")
                            ->orWhere('custom_id', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('impact')) {
            $query->where('impact', $request->impact);
        }

        if ($request->filled('issue_owner')) {
            $query->where('action_by', $request->issue_owner);
        }

        $today = Carbon::today();
        if ($request->filled('priority')) {
            $priority = $request->string('priority')->toString();
            $query->where(function ($issues) use ($priority, $today) {
                if ($priority === 'Done') {
                    $issues->where('status', 'Closed');
                } elseif ($priority === 'Unscheduled') {
                    $issues->where(function ($status) {
                        $status->whereNull('status')->orWhere('status', '!=', 'Closed');
                    })->whereNull('planned_resolution_date');
                } else {
                    $issues->where(function ($status) {
                        $status->whereNull('status')->orWhere('status', '!=', 'Closed');
                    })->whereNotNull('planned_resolution_date');

                    match ($priority) {
                        'Overdue' => $issues->whereDate('planned_resolution_date', '<', $today),
                        'High' => $issues->whereDate('planned_resolution_date', '>=', $today)
                            ->whereDate('planned_resolution_date', '<', $today->copy()->addDays(7)),
                        'Medium' => $issues->whereDate('planned_resolution_date', '>=', $today->copy()->addDays(7))
                            ->whereDate('planned_resolution_date', '<', $today->copy()->addDays(14)),
                        'Low' => $issues->whereDate('planned_resolution_date', '>=', $today->copy()->addDays(14)),
                    };
                }
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $notClosed = fn ($q) => $q->where(function ($s) {
            $s->whereNull('status')->orWhere('status', '!=', 'Closed');
        });

        $stats = [
            'total' => (clone $query)->count(),
            'raised' => (clone $query)->where('status', 'Raised')->count(),
            'open' => (clone $query)->where('status', 'Open')->count(),
            'closed' => (clone $query)->where('status', 'Closed')->count(),
            'overdue' => (clone $query)->where($notClosed)->whereNotNull('planned_resolution_date')
                ->whereDate('planned_resolution_date', '<', $today)->count(),
            'high' => (clone $query)->where($notClosed)->whereNotNull('planned_resolution_date')
                ->whereDate('planned_resolution_date', '>=', $today)
                ->whereDate('planned_resolution_date', '<', $today->copy()->addDays(7))->count(),
            'medium' => (clone $query)->where($notClosed)->whereNotNull('planned_resolution_date')
                ->whereDate('planned_resolution_date', '>=', $today->copy()->addDays(7))
                ->whereDate('planned_resolution_date', '<', $today->copy()->addDays(14))->count(),
            'low' => (clone $query)->where($notClosed)->whereNotNull('planned_resolution_date')
                ->whereDate('planned_resolution_date', '>=', $today->copy()->addDays(14))->count(),
        ];

        $issues = $query->orderByDesc('raised_date')->paginate(15)->withQueryString();
        $projectOptionsQuery = Project::orderBy('project_name');
        ProjectOrganizationFilters::apply($projectOptionsQuery, $request, true);
        $projects = $projectOptionsQuery->get(['id', 'project_code', 'project_name']);
        $owners = Issue::query()->whereNotNull('action_by')->where('action_by', '!=', '')
            ->distinct()->orderBy('action_by')->pluck('action_by');

        return view('admin.projects.issues.history', array_merge(
            compact('issues', 'projects', 'stats', 'owners'),
            ProjectOrganizationFilters::options($request)
        ));
    }

    /**
     * Remove the specified issue from storage.
     */
    public function destroy(Issue $issue)
    {
        $user = auth()->user();
        abort_unless(($user->isAdmin() || $user->isSuperadmin()) && $user->canManageProject($issue->project), 403);

        $issue->delete();

        return back()->with('success', 'Issue deleted successfully.');
    }

    private function authorizeIssueMutation(Issue $issue): void
    {
        $user = auth()->user();

        abort_unless(
            ($user->isAdmin() || $user->isSuperadmin()) && $user->canManageProject($issue->project)
            || ($user->isUser()
                && $issue->project
                && $user->canManageProject($issue->project)),
            403
        );
    }
}
