<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use Carbon\Carbon;
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

        if ($request->filled('project_sector') && $request->project_sector !== 'All Sectors') {
            $query->where('project_type', $request->project_sector);
        }

        if ($request->filled('delivery_method') && $request->delivery_method !== 'All Methods') {
            $query->where('delivery_method', $request->delivery_method);
        }

        if ($request->filled('consultant_type') && $request->consultant_type !== 'All Types') {
            $query->where('consultancy_sector', $request->consultant_type);
        }

        if ($request->filled('budget_performance') && $request->budget_performance !== 'All') {
            if ($request->budget_performance === 'Above Budget') {
                $query->whereRaw('IFNULL(revised_budget_amount, 0) > IFNULL(contract_budget, 0)');
            } elseif ($request->budget_performance === 'Under Budget') {
                $query->whereRaw('IFNULL(revised_budget_amount, 0) < IFNULL(contract_budget, 0)');
            } elseif ($request->budget_performance === 'On Budget') {
                $query->whereRaw('IFNULL(revised_budget_amount, 0) = IFNULL(contract_budget, 0)');
            }
        }

        if ($request->filled('cost_performance') && $request->cost_performance !== 'All') {
            if ($request->cost_performance === 'Above Allowable Cost') {
                $query->whereRaw('IFNULL(cost_at_completion, 0) > IFNULL(total_allowable_cost, 0)');
            } elseif ($request->cost_performance === 'Below Allowable Cost') {
                $query->whereRaw('IFNULL(cost_at_completion, 0) < IFNULL(total_allowable_cost, 0)');
            } elseif ($request->cost_performance === 'With Allowable Cost') {
                $query->whereRaw('IFNULL(cost_at_completion, 0) = IFNULL(total_allowable_cost, 0)');
            }
        }

        if ($request->filled('schedule_performance') && $request->schedule_performance !== 'All') {
            if ($request->schedule_performance === 'Not Started') {
                $query->whereNull('actual_start_date');
            } elseif ($request->schedule_performance === 'Behind Schedule') {
                $query->whereNotNull('actual_start_date')->whereNotNull('baseline_finish_date')->whereRaw('DATEDIFF(baseline_finish_date, actual_start_date) < 0');
            } elseif ($request->schedule_performance === 'Ahead of Schedule') {
                $query->whereNotNull('actual_start_date')->whereNotNull('baseline_finish_date')->whereRaw('DATEDIFF(baseline_finish_date, actual_start_date) > 0');
            } elseif ($request->schedule_performance === 'On Schedule') {
                $query->where(function ($q) {
                    $q->whereNotNull('actual_start_date')->whereNull('baseline_finish_date')
                      ->orWhereNotNull('actual_start_date')->whereNotNull('baseline_finish_date')->whereRaw('DATEDIFF(baseline_finish_date, actual_start_date) = 0');
                });
            }
        }

        if ($request->filled('project_status') && $request->project_status !== 'All') {
            $query->where('status', $request->project_status);
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
        $selectedProjectId = $request->filled('project_id') ? $request->project_id : null;
        $selectedSector = $request->filled('sector') ? $request->sector : null;

        $effectiveDataDate = $dataDate;
        if (!$effectiveDataDate || \App\Models\ProjectProgressUpdate::query()
            ->whereDate('project_progress_updates.data_date', $effectiveDataDate)
            ->count() === 0
        ) {
            $effectiveDataDate = \App\Models\ProjectProgressUpdate::query()
                ->whereNotNull('data_date')
                ->max('data_date');
        }

        $progressQueryDataDate = \App\Models\ProjectProgressUpdate::query()
            ->whereDate('project_progress_updates.data_date', $effectiveDataDate);
        
        if ($selectedProjectId) {
            $progressQueryDataDate->where('project_progress_updates.project_id', $selectedProjectId);
        }
        
        $projectIdsWithUpdates = $progressQueryDataDate->distinct('project_id')->pluck('project_id');

        $allProjectIdsWithUpdates = \App\Models\ProjectProgressUpdate::query()
            ->distinct('project_id')
            ->pluck('project_id');

        $projectsQuery = Project::query();
        if ($selectedProjectId) {
            $projectsQuery->where('id', $selectedProjectId);
        } elseif ($selectedSector) {
            $projectsQuery->where('project_type', $selectedSector);
        }
        if ($allProjectIdsWithUpdates->isNotEmpty()) {
            $projectsQuery->whereIn('id', $allProjectIdsWithUpdates);
        }
        $projects = $projectsQuery->get();

        $totalPortfolioValue = $projects->sum(fn($p) => (float) $p->total_project_value);
        $totalAllowableCost = $projects->sum(fn($p) => (float) $p->total_allowable_cost);
        $totalCostAtCompletion = $projects->sum(fn($p) => (float) $p->cost_at_completion);
        $totalVariations = $projects->sum(fn($p) => (float) $p->variation);

        $costVariance = $totalPortfolioValue - $totalCostAtCompletion;
        $avgScheduleVariance = $projects->count() > 0 ? $projects->avg(fn($p) => $p->schedule_variance) : 0;

        $projectTypes = Project::whereIn('id', $allProjectIdsWithUpdates)
            ->when($selectedSector, fn($q) => $q->where('project_type', $selectedSector))
            ->when($selectedProjectId, fn($q) => $q->where('id', $selectedProjectId))
            ->groupBy('project_type')
            ->selectRaw('project_type, count(*) as count')
            ->pluck('count', 'project_type');

        $clientValues = Project::whereIn('id', $allProjectIdsWithUpdates)
            ->when($selectedSector, fn($q) => $q->where('project_type', $selectedSector))
            ->when($selectedProjectId, fn($q) => $q->where('id', $selectedProjectId))
            ->whereNotNull('project_client')
            ->selectRaw('project_client, sum(contract_budget + variation + supplementary) as total_value')
            ->groupBy('project_client')
            ->pluck('total_value', 'project_client');

        $sectorValues = Project::whereIn('id', $allProjectIdsWithUpdates)
            ->when($selectedSector, fn($q) => $q->where('project_type', $selectedSector))
            ->when($selectedProjectId, fn($q) => $q->where('id', $selectedProjectId))
            ->whereNotNull('project_type')
            ->groupBy('project_type')
            ->selectRaw('project_type, count(*) as project_count')
            ->pluck('project_count', 'project_type');

        $puBaseScurve = function () {
            return \App\Models\ProjectProgressUpdate::query();
        };

        $puBase = function () use ($effectiveDataDate, $selectedProjectId, $selectedSector) {
            $q = \App\Models\ProjectProgressUpdate::query()
                ->whereDate('project_progress_updates.data_date', $effectiveDataDate);
            if ($selectedProjectId) {
                $q->where('project_progress_updates.project_id', $selectedProjectId);
            } elseif ($selectedSector) {
                $q->whereHas('project', fn($pq) => $pq->where('project_type', $selectedSector));
            }
            return $q;
        };

        $totalProjectsFromUpdates = $puBase()->select('project_id')->distinct()->count('project_id');

        $allProjectsWithUpdates = \App\Models\ProjectProgressUpdate::query()
            ->join('projects', 'project_progress_updates.project_id', '=', 'projects.id')
            ->select('projects.id', 'projects.project_code', 'projects.project_name')
            ->distinct()
            ->orderBy('projects.project_code');

        if ($selectedProjectId) {
            $allProjectsWithUpdates = $allProjectsWithUpdates->where('project_progress_updates.project_id', $selectedProjectId);
        } elseif ($selectedSector) {
            $allProjectsWithUpdates = $allProjectsWithUpdates->where('projects.project_type', $selectedSector);
        }

        $allProjectsWithUpdates = $allProjectsWithUpdates->get();
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

        $sCurveData = $puBaseScurve()->whereNotNull('data_date')->with('project')
            ->orderBy('data_date', 'asc')
            ->get()
            ->groupBy('data_date')
            ->map(function ($updates) {
                $spiValues = $updates->filter(fn($u) => $u->progress_planned > 0)->map(fn($u) => ($u->progress_actual / $u->progress_planned) * 100);
                $cpiValues = $updates->filter(fn($u) => $u->cost_actual > 0)->map(fn($u) => $u->cost_planned / $u->cost_actual);
                $dateSource = $updates->first()->data_date ?: $updates->first()->created_at;
                return [
                    'date' => $dateSource ? \Carbon\Carbon::parse($dateSource)->format('M d, Y') : null,
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
            'dataDate',
            'effectiveDataDate',
            'allProjectsWithUpdates',
            'selectedProjectId',
            'selectedSector'
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

    public function issuesReport(Request $request)
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

        if ($request->filled('project_id') && $request->project_id !== 'all') {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('impact') && $request->impact !== 'all') {
            $query->where('impact', $request->impact);
        }

        if ($request->filled('issue_type') && $request->issue_type !== 'all') {
            $query->where('issue_type', $request->issue_type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('raised_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('raised_date', '<=', $request->to_date);
        }

        $allIssues = $query->get();

        $total = $allIssues->count();
        $open = $allIssues->where('status', 'Open')->count();
        $inProgress = $allIssues->where('status', 'In Progress')->count();
        $closed = $allIssues->where('status', 'Closed')->count();
        $cancelled = $allIssues->where('status', 'Cancelled')->count();

        $today = Carbon::today();
        $overdue = $allIssues->filter(function ($issue) use ($today) {
            return $issue->planned_resolution_date
                && $issue->planned_resolution_date->isPast()
                && !in_array($issue->status, ['Closed', 'Cancelled']);
        })->count();

        $highImpact = $allIssues->where('impact', 'High')->count();
        $mediumImpact = $allIssues->where('impact', 'Medium')->count();
        $lowImpact = $allIssues->where('impact', 'Low')->count();

        $statusCounts = [
            'Open' => $open,
            'In Progress' => $inProgress,
            'Closed' => $closed,
            'Cancelled' => $cancelled,
        ];

        $impactCounts = [
            'High' => $highImpact,
            'Medium' => $mediumImpact,
            'Low' => $lowImpact,
        ];

        $issueTypeCounts = $allIssues->groupBy('issue_type')->map->count()->sortDesc()->take(10);
        $sectorCounts = $allIssues->groupBy(function ($issue) {
            return $issue->project?->project_type ?? 'Unknown';
        })->map->count()->sortDesc()->take(8);

        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            $label = $monthStart->format('M Y');
            $count = $allIssues->filter(function ($issue) use ($monthStart, $monthEnd) {
                return $issue->raised_date && $issue->raised_date->between($monthStart, $monthEnd);
            })->count();
            $monthlyTrend[$label] = $count;
        }

        $topProjects = $allIssues->groupBy('project_id')
            ->map(function ($group) {
                $project = $group->first()->project;
                return [
                    'project_name' => $project?->project_name ?? 'Unknown',
                    'project_code' => $project?->project_code ?? 'N/A',
                    'count' => $group->count(),
                    'open' => $group->where('status', 'Open')->count(),
                    'closed' => $group->where('status', 'Closed')->count(),
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values();

        $avgResolutionDays = $allIssues->filter(function ($issue) {
            return $issue->raised_date && $issue->issue_closed_date;
        })->map(function ($issue) {
            return $issue->raised_date->diffInDays($issue->issue_closed_date);
        })->avg();

        $resolutionTimesArray = $allIssues->filter(function ($issue) {
            return $issue->raised_date && $issue->issue_closed_date;
        })->map(function ($issue) {
            return $issue->raised_date->diffInDays($issue->issue_closed_date);
        })->sort()->values()->all();

        $p50 = !empty($resolutionTimesArray) ? $resolutionTimesArray[max(0, floor((count($resolutionTimesArray) - 1) * 0.5))] : 0;
        $p90 = !empty($resolutionTimesArray) ? $resolutionTimesArray[max(0, floor((count($resolutionTimesArray) - 1) * 0.9))] : 0;

        $projects = Project::orderBy('project_name')->get(['id', 'project_code', 'project_name']);

        $issues = $query->orderByDesc('raised_date')->paginate(15)->withQueryString();

        return view('admin.projects.issues-report', compact(
            'issues',
            'projects',
            'total',
            'open',
            'inProgress',
            'closed',
            'cancelled',
            'overdue',
            'highImpact',
            'mediumImpact',
            'lowImpact',
            'statusCounts',
            'impactCounts',
            'issueTypeCounts',
            'sectorCounts',
            'monthlyTrend',
            'topProjects',
            'avgResolutionDays',
            'p50',
            'p90',
        ));
    }
}
