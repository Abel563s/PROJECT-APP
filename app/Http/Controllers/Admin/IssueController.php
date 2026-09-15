<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Project;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Display a listing of in-progress projects for issue management.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        $status = $request->filled('status') ? $request->status : 'in progress';
        if ($status === 'in progress') {
            $query->where('status', 'in progress');
        } elseif ($status === 'completed') {
            $query->where('status', 'completed');
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

        $projects = $query->orderByDesc('baseline_start_date')->paginate(15)->withQueryString();
        return view('admin.projects.issues.index', compact('projects'));
    }

    /**
     * Manage issues for a specific project.
     */
    public function manage(Project $project)
    {
        $issues = Issue::where('project_id', $project->id)
            ->orderByDesc('raised_date')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Issue::where('project_id', $project->id)->count(),
            'open' => Issue::where('project_id', $project->id)->where('status', 'Open')->count(),
            'in_progress' => Issue::where('project_id', $project->id)->where('status', 'In Progress')->count(),
            'closed' => Issue::where('project_id', $project->id)->where('status', 'Closed')->count(),
            'cancelled' => Issue::where('project_id', $project->id)->where('status', 'Cancelled')->count(),
        ];

        return view('admin.projects.issues.manage', compact('project', 'issues', 'stats'));
    }

    /**
     * Show the form for creating a new issue (modal partial).
     */
    public function create(Project $project)
    {
        return view('admin.projects.issues.create-modal', compact('project'))->render();
    }

    /**
     * Store a newly created issue in storage.
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'issue_type' => 'nullable|string|max:255',
            'issue' => 'required|string|max:255',
            'description' => 'nullable|string',
            'area_affected' => 'nullable|string',
            'impact' => 'nullable|in:Low,Medium,High',
            'status' => 'nullable|in:Open,Closed,In Progress,Cancelled',
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
        if (request()->has('partial')) {
            $projects = Project::orderBy('project_name')->get(['id', 'project_code', 'project_name', 'project_type', 'consultancy_sector']);
            return view('admin.projects.issues.edit-modal', compact('issue', 'projects'))->render();
        }

        return redirect()->route('admin.projects.issues.index');
    }

    /**
     * Update the specified issue in storage.
     */
    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'sector' => 'nullable|string|max:255',
            'project_code' => 'nullable|string|max:255',
            'issue_type' => 'nullable|string|max:255',
            'issue' => 'required|string|max:255',
            'description' => 'nullable|string',
            'area_affected' => 'nullable|string',
            'impact' => 'nullable|in:Low,Medium,High',
            'status' => 'nullable|in:Open,Closed,In Progress,Cancelled',
            'raised_date' => 'nullable|date',
            'raised_by' => 'nullable|string|max:255',
            'action_plan' => 'nullable|string',
            'planned_resolution_date' => 'nullable|date',
            'action_by' => 'nullable|string|max:255',
            'issue_closed_date' => 'nullable|date',
            'remark' => 'nullable|string',
        ]);

        $validated['last_updated_date'] = now();

        $issue->update($validated);

        return back()->with('success', 'Issue updated successfully.');
    }

    /**
     * Display issue history across all projects.
     */
    public function history(Request $request)
    {
        $query = Issue::query()->with('project');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('issue', 'like', "%{$search}%")
                    ->orWhere('issue_type', 'like', "%{$search}%")
                    ->orWhere('raised_by', 'like', "%{$search}%")
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

        if ($request->filled('issue_type')) {
            $query->where('issue_type', $request->issue_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $issues = $query->orderByDesc('raised_date')->paginate(15)->withQueryString();
        $projects = \App\Models\Project::orderBy('project_name')->get(['id', 'project_code', 'project_name']);

        return view('admin.projects.issues.history', compact('issues', 'projects'));
    }

    /**
     * Remove the specified issue from storage.
     */
    public function destroy(Issue $issue)
    {
        $issue->delete();

        return back()->with('success', 'Issue deleted successfully.');
    }
}
