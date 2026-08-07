<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('custom_id', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhere('project_client', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type') && $request->type !== 'All Modules') {
            $query->where('project_type', $request->type);
        }

        if ($request->filled('delivery_method') && $request->delivery_method !== 'All Methods') {
            $query->where('delivery_method', $request->delivery_method);
        }

        if ($request->filled('consultancy_sector') && $request->consultancy_sector !== 'All Sectors') {
            $query->where('consultancy_sector', $request->consultancy_sector);
        }

        if ($request->filled('consultant') && $request->consultant !== 'All Consultants') {
            $query->where('consultant', $request->consultant);
        }

        $projects = $query->orderByDesc('baseline_start_date')->paginate(15)->withQueryString();

        $consultantTypes = Project::whereNotNull('consultant')
            ->where('consultant', '!=', '')
            ->distinct()
            ->orderBy('consultant')
            ->pluck('consultant');

        return view('admin.projects.index', compact('projects', 'consultantTypes'));
    }

    public function create()
    {
        if (request()->has('partial')) {
            return view('admin.projects.create-modal');
        }
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_code' => 'nullable|string',
            'project_name' => 'required|string',
            'status' => 'nullable|in:in progress,completed',
            'project_type' => 'nullable|in:Building,Fit-Out,Infrastructure,Mixed (Bui/Road),Mixed (Bui/Fit-Out)',
            'delivery_method' => 'nullable|in:DB,DBB,DB-LS,DB-ADM,DBB-ADM,DB-CP',
            'project_client' => 'nullable|string',
            'consultant' => 'nullable|string',
            'consultancy_sector' => 'nullable|in:Government,Private,Client / Engineering Team',
            'scope' => 'nullable|string',
            'contract_budget' => 'nullable|numeric',
            'variation' => 'nullable|numeric',
            'supplementary' => 'nullable|numeric',
            'budget_at_completion' => 'nullable|numeric',
            'total_allowable_cost' => 'nullable|numeric',
            'cost_at_completion' => 'nullable|numeric',
            'revised_budget_amount' => 'nullable|numeric',
            'baseline_start_date' => 'nullable|date',
            'baseline_finish_date' => 'nullable|date',
            'actual_start_date' => 'nullable|date',
            'actual_finish_date' => 'nullable|date',
            'approved_eot' => 'nullable|date',
            'revision_number' => 'nullable|string',
            'closing_status' => 'nullable|in:FA Received,PA Received,PPA Received,Snag / Di-Snag,Waiting for PA,Not Completed',
            'ppa_received_at' => 'nullable|date',
            'pa_received_at' => 'nullable|date',
            'fa_received_at' => 'nullable|date',
        ]);

        $validated['status'] = $validated['status'] ?? 'in progress';

        Project::create($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function analytics(Request $request)
    {
        $dataDate = $request->filled('data_date') ? $request->data_date : null;

        $progressQueryDataDate = \App\Models\ProjectProgressUpdate::query();
        if ($dataDate) {
            $progressQueryDataDate->whereDate('project_progress_updates.data_date', $dataDate);
        }
        $projectIdsWithUpdates = $progressQueryDataDate->distinct('project_id')->pluck('project_id');

        if ($dataDate && $projectIdsWithUpdates->isEmpty()) {
            $projectIdsWithUpdates = \App\Models\ProjectProgressUpdate::query()->distinct('project_id')->pluck('project_id');
        }

        $projectsQuery = Project::query();
        if ($projectIdsWithUpdates->isNotEmpty()) {
            $projectsQuery->whereIn('id', $projectIdsWithUpdates);
        }
        $projects = $projectsQuery->get();

        $totalPortfolioValue = $projects->sum(fn($p) => (float) $p->total_project_value);
        $totalAllowableCost = $projects->sum(fn($p) => (float) $p->total_allowable_cost);
        $totalCostAtCompletion = $projects->sum(fn($p) => (float) $p->cost_at_completion);
        $totalVariations = $projects->sum(fn($p) => (float) $p->variation);

        $costVariance = $totalPortfolioValue - $totalCostAtCompletion;
        $avgScheduleVariance = $projects->count() > 0 ? $projects->avg(fn($p) => $p->schedule_variance) : 0;

        $projectTypes = Project::whereIn('id', $projectIdsWithUpdates)
            ->groupBy('project_type')
            ->selectRaw('project_type, count(*) as count')
            ->pluck('count', 'project_type');

        $clientValues = Project::whereIn('id', $projectIdsWithUpdates)
            ->whereNotNull('project_client')
            ->selectRaw('project_client, sum(contract_budget + variation + supplementary) as total_value')
            ->groupBy('project_client')
            ->pluck('total_value', 'project_client');

        $sectorValues = Project::whereIn('id', $projectIdsWithUpdates)
            ->whereNotNull('project_type')
            ->groupBy('project_type')
            ->selectRaw('project_type, count(*) as project_count')
            ->pluck('project_count', 'project_type');

        $puBaseScurve = function () {
            return \App\Models\ProjectProgressUpdate::query();
        };

        $useUnfilteredData = false;
        if ($dataDate) {
            $filteredCount = \App\Models\ProjectProgressUpdate::query()
                ->whereDate('project_progress_updates.data_date', $dataDate)
                ->count();
            if ($filteredCount === 0) {
                $useUnfilteredData = true;
            }
        }

        $puBase = function () use ($dataDate, $useUnfilteredData) {
            $q = \App\Models\ProjectProgressUpdate::query();
            if ($dataDate && !$useUnfilteredData) {
                $q->whereDate('project_progress_updates.data_date', $dataDate);
            }
            return $q;
        };

        $totalProjectsFromUpdates = $puBase()->select('project_id')->distinct()->count('project_id');
        $totalRevenuePlanned = $puBase()->sum('revenue_planned');
        $totalRevenueActual = $puBase()->sum('revenue_actual');
        $totalCostActual = $puBase()->sum('cost_actual');

        $sectorBreakdown = $puBase()->select('projects.project_type')
            ->join('projects', 'project_progress_updates.project_id', '=', 'projects.id')
            ->groupBy('projects.project_type')
            ->selectRaw('projects.project_type, count(distinct project_progress_updates.project_id) as count')
            ->pluck('count', 'projects.project_type');

        $evmData = $puBase()->selectRaw('
                projects.project_code,
                projects.project_name,
                SUM(project_progress_updates.revenue_planned) as total_pv,
                SUM(project_progress_updates.revenue_actual) as total_ev,
                SUM(project_progress_updates.cost_actual) as total_ac
            ')
            ->join('projects', 'project_progress_updates.project_id', '=', 'projects.id')
            ->groupBy('project_progress_updates.project_id', 'projects.project_code', 'projects.project_name')
            ->orderBy('total_pv', 'desc')
            ->get();

        $sCurveData = $puBaseScurve()->with('project')
            ->orderBy('data_date', 'asc')
            ->get()
            ->groupBy('data_date')
            ->map(function ($updates) {
                $spiValues = $updates->filter(fn($u) => $u->progress_planned > 0)->map(fn($u) => ($u->progress_actual / $u->progress_planned) * 100);
                $cpiValues = $updates->filter(fn($u) => $u->cost_actual > 0)->map(fn($u) => $u->cost_planned / $u->cost_actual);
                return [
                    'date' => $updates->first()->data_date ? \Carbon\Carbon::parse($updates->first()->data_date)->format('M d, Y') : null,
                    'spi' => $spiValues->isNotEmpty() ? round($spiValues->avg(), 2) : 0,
                    'cpi' => $cpiValues->isNotEmpty() ? round($cpiValues->avg(), 2) : 0,
                    'count' => $updates->count(),
                ];
            })
            ->values();

        $latestSpi = null;
        $latestCpi = null;
        $spiTrend = 'stable';
        $cpiTrend = 'stable';
        if ($sCurveData->count() > 0) {
            $allUpdates = $sCurveData->sortBy('date');
            $latest = $allUpdates->last();
            $previous = $allUpdates->count() > 1 ? $allUpdates->slice(-2, 1)->first() : null;
            if ($latest) {
                $latestSpi = $latest['spi'];
                $latestCpi = $latest['cpi'];

                if ($previous) {
                    if ($latestSpi > $previous['spi']) $spiTrend = 'improving';
                    elseif ($latestSpi < $previous['spi']) $spiTrend = 'declining';

                    if ($latestCpi > $previous['cpi']) $cpiTrend = 'improving';
                    elseif ($latestCpi < $previous['cpi']) $cpiTrend = 'declining';
                }
            }
        }

        $latestProgressUpdates = $puBase()->select(
                'project_progress_updates.project_id',
                'projects.project_type',
                'projects.revised_budget_amount',
                'projects.contract_budget',
                'projects.variation',
                'projects.supplementary',
                'projects.consultancy_sector',
                'project_progress_updates.progress_planned',
                'project_progress_updates.progress_actual',
                'project_progress_updates.cost_planned',
                'project_progress_updates.cost_actual'
            )
            ->join('projects', 'project_progress_updates.project_id', '=', 'projects.id')
            ->orderByDesc('project_progress_updates.created_at')
            ->get()
            ->unique('project_id')
            ->values()
            ->map(function ($item) {
                $revised = (float) ($item->revised_budget_amount ?? 0);
                $budget = $revised > 0
                    ? $revised
                    : (float) ($item->contract_budget ?? 0)
                        + (float) ($item->variation ?? 0)
                        + (float) ($item->supplementary ?? 0);
                $item->budget_amount = $budget;
                return $item;
            });

        $avgWeightedSpi = 0;
        $avgWeightedCpi = 0;
        $sectorWeightedSpi = collect();
        $sectorWeightedCpi = collect();

        if ($latestProgressUpdates->isNotEmpty()) {
            $totalBudget = $latestProgressUpdates->sum(fn($u) => $u->budget_amount);

            if ($totalBudget > 0) {
                $weightedSpiSum = 0;
                $weightedCpiSum = 0;
                $sectorSpi = [];
                $sectorCpi = [];

                foreach ($latestProgressUpdates as $update) {
                    $weight = $update->budget_amount / $totalBudget;

                    $spi = $update->progress_planned > 0
                        ? ($update->progress_actual / $update->progress_planned) * 100
                        : 0;
                    $cpi = $update->cost_actual > 0
                        ? $update->cost_planned / $update->cost_actual
                        : 0;

                    $weightedSpiSum += $spi * $weight;
                    $weightedCpiSum += $cpi * $weight;

                    $sector = $update->project_type ?: 'Unspecified';
                    $sectorSpi[$sector] = ($sectorSpi[$sector] ?? 0) + ($spi * $weight);
                    $sectorCpi[$sector] = ($sectorCpi[$sector] ?? 0) + ($cpi * $weight);
                }

                $avgWeightedSpi = $weightedSpiSum;
                $avgWeightedCpi = $weightedCpiSum;
                $sectorWeightedSpi = collect($sectorSpi)->sortDesc();
                $sectorWeightedCpi = collect($sectorCpi)->sortDesc();
            }
        }

        $schedulePerformanceData = $puBase()->select(
                'project_progress_updates.project_id',
                'projects.project_code',
                'projects.project_name',
                'project_progress_updates.progress_planned',
                'project_progress_updates.progress_actual',
                'project_progress_updates.created_at as latest_update'
            )
            ->join('projects', 'project_progress_updates.project_id', '=', 'projects.id')
            ->whereIn('projects.status', ['in progress', 'in_progress'])
            ->orderByDesc('project_progress_updates.created_at')
            ->get()
            ->unique('project_id')
            ->values()
            ->map(function ($item) {
                $planned = round($item->progress_planned, 1);
                $actual = round($item->progress_actual, 1);
                $variance = $actual - $planned;
                return [
                    'project_code' => $item->project_code,
                    'project_name' => $item->project_name,
                    'planned' => $planned,
                    'actual' => $actual,
                    'variance' => $variance,
                    'latest_update' => $item->latest_update,
                ];
            })
            ->values();

        return view('admin.projects.analytics', compact(
            'totalPortfolioValue',
            'totalAllowableCost',
            'totalCostAtCompletion',
            'totalVariations',
            'costVariance',
            'avgScheduleVariance',
            'projectTypes',
            'clientValues',
            'sectorValues',
            'projects',
            'totalProjectsFromUpdates',
            'totalRevenuePlanned',
            'totalRevenueActual',
            'totalCostActual',
            'sectorBreakdown',
            'evmData',
            'sCurveData',
            'schedulePerformanceData',
            'avgWeightedSpi',
            'avgWeightedCpi',
            'sectorWeightedSpi',
            'sectorWeightedCpi',
            'dataDate'
        ));
    }

    public function show(Project $project)
    {
        if (request()->has('partial')) {
            return view('admin.projects.show-modal', compact('project'));
        }
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        if (request()->has('partial')) {
            return view('admin.projects.edit-modal', compact('project'));
        }
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'project_code' => 'nullable|string',
            'project_name' => 'required|string',
            'status' => 'nullable|in:in progress,completed',
            'project_type' => 'nullable|in:Building,Fit-Out,Infrastructure,Mixed (Bui/Road),Mixed (Bui/Fit-Out)',
            'delivery_method' => 'nullable|in:DB,DBB,DB-LS,DB-ADM,DBB-ADM,DB-CP',
            'project_client' => 'nullable|string',
            'consultant' => 'nullable|string',
            'consultancy_sector' => 'nullable|in:Government,Private,Client / Engineering Team',
            'scope' => 'nullable|string',
            'contract_budget' => 'nullable|numeric',
            'variation' => 'nullable|numeric',
            'supplementary' => 'nullable|numeric',
            'budget_at_completion' => 'nullable|numeric',
            'total_allowable_cost' => 'nullable|numeric',
            'cost_at_completion' => 'nullable|numeric',
            'revised_budget_amount' => 'nullable|numeric',
            'baseline_start_date' => 'nullable|date',
            'baseline_finish_date' => 'nullable|date',
            'actual_start_date' => 'nullable|date',
            'actual_finish_date' => 'nullable|date',
            'approved_eot' => 'nullable|date',
            'revision_number' => 'nullable|string',
            'closing_status' => 'nullable|in:FA Received,PA Received,PPA Received,Snag / Di-Snag,Waiting for PA,Not Completed',
            'ppa_received_at' => 'nullable|date',
            'pa_received_at' => 'nullable|date',
            'fa_received_at' => 'nullable|date',
        ]);

        $project->update($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function closeoutIndex(Request $request)
    {
        $query = Project::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhere('custom_id', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->paginate(15)->withQueryString();
        return view('admin.projects.closeout_index', compact('projects'));
    }

    public function closeoutShow(Project $project)
    {
        return view('admin.projects.closeout', compact('project'));
    }

    public function rorIndex()
    {
        return view('admin.projects.ror');
    }

    public function sepIndex()
    {
        return view('admin.projects.sep');
    }

    public function search(Request $request)
    {
        $search = $request->get('q', '');
        $projects = Project::where('status', 'active')
            ->where(function ($query) use ($search) {
                $query->where('project_name', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhere('custom_id', 'like', "%{$search}%");
            })
            ->orderBy('project_name')
            ->limit(20)
            ->get(['id', 'project_code', 'project_name']);

        return response()->json($projects->map(function ($project) {
            return [
                'id' => $project->id,
                'text' => $project->project_code . ' - ' . $project->project_name,
                'project_code' => $project->project_code,
                'project_name' => $project->project_name,
            ];
        }));
    }

    public function toggleStatus(Project $project)
    {
        $statuses = ['in progress', 'completed', 'suspended'];
        $currentIndex = array_search($project->status, $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);
        $newStatus = $statuses[$nextIndex];
        $project->update(['status' => $newStatus]);

        return response()->json(['success' => true, 'status' => $newStatus]);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    public function nextCode(Request $request)
    {
        $prefix = strtoupper($request->query('prefix', 'PRJ'));

        $lastCode = Project::where('project_code', 'like', "{$prefix}-%")
            ->orderByRaw('CAST(SUBSTRING_INDEX(project_code, "-", -1) AS UNSIGNED) DESC')
            ->value('project_code');

        $nextSequence = 1;
        if ($lastCode) {
            $parts = explode('-', $lastCode);
            $lastSequence = (int) end($parts);
            $nextSequence = $lastSequence + 1;
        }

        return response()->json([
            'code' => sprintf('%s-%03d', $prefix, $nextSequence)
        ]);
    }

    public function issuesIndex(Request $request)
    {
        $query = Issue::query()->with('project');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('issue', 'like', "%{$search}%")
                    ->orWhere('issue_type', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhere('raised_by', 'like', "%{$search}%");
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('partial') && $request->get('action') === 'create') {
            $projects = Project::orderBy('project_name')->get(['id', 'project_code', 'project_name', 'project_type', 'consultancy_sector']);
            return view('admin.projects.issues.create-modal', compact('projects'))->render();
        }

        $issues = $query->orderByDesc('raised_date')->paginate(15)->withQueryString();
        $projects = Project::orderBy('project_name')->get(['id', 'project_code', 'project_name', 'project_type', 'consultancy_sector']);

        return view('admin.projects.issues', compact('issues', 'projects'));
    }

    public function issuesEdit(Request $request, Issue $issue)
    {
        if ($request->has('partial')) {
            $projects = Project::orderBy('project_name')->get(['id', 'project_code', 'project_name', 'project_type', 'consultancy_sector']);
            return view('admin.projects.issues.edit-modal', compact('issue', 'projects'))->render();
        }

        return redirect()->route('admin.projects.issues.index');
    }

    public function issuesShow(Request $request, Issue $issue)
    {
        if ($request->has('partial')) {
            return view('admin.projects.issues.view-modal', compact('issue'))->render();
        }

        return redirect()->route('admin.projects.issues.index');
    }

    public function issuesStore(Request $request)
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
            'date' => 'required|date',
            'raised_date' => 'nullable|date',
            'raised_by' => 'nullable|string|max:255',
            'action_plan' => 'nullable|string',
            'planned_resolution_date' => 'nullable|date',
            'action_by' => 'nullable|string|max:255',
            'issue_closed_date' => 'nullable|date',
            'remark' => 'nullable|string',
        ]);

        $validated['last_updated_date'] = now();

        $issue = Issue::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'issue' => $issue]);
        }

        return redirect()->back()->with('success', 'Issue created successfully.');
    }

    public function issuesUpdate(Request $request, Issue $issue)
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

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'issue' => $issue]);
        }

        return redirect()->back()->with('success', 'Issue updated successfully.');
    }

    public function issuesDestroy(Issue $issue)
    {
        $issue->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Issue deleted successfully.');
    }

    public function issuesReport()
    {
        return view('admin.projects.issues-report');
    }
}
