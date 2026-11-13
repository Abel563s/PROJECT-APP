<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use App\Models\EotCategory;
use App\Models\EotEntry;
use App\Models\Issue;
use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\ProjectRiskPool;
use App\Models\RiskCategory;
use App\Models\Sector;
use App\Support\ProjectOrganizationFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'business_unit_id' => ['nullable', 'integer', Rule::exists('business_units', 'id')],
            'sector_id' => ['nullable', 'integer', Rule::exists('sectors', 'id')],
        ]);

        $query = Project::with('coordinators');
        $businessUnitFilterId = ProjectOrganizationFilters::effectiveBusinessUnitId($request);

        if ($businessUnitFilterId) {
            $query->where('business_unit_id', $businessUnitFilterId);
        }

        if ($request->filled('sector_id')) {
            $query->where('sector_id', $request->input('sector_id'));
        }

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
            } elseif ($request->cost_performance === 'Within Allowable Cost') {
                $query->whereRaw('IFNULL(cost_at_completion, 0) = IFNULL(total_allowable_cost, 0)');
            }
        }

        if ($request->filled('schedule_performance') && $request->schedule_performance !== 'All') {
            if ($request->schedule_performance === 'Not Started') {
                $query->whereNull('actual_start_date');
            } elseif ($request->schedule_performance === 'Behind Schedule') {
                $query->whereNotNull('actual_start_date')->whereNotNull('baseline_finish_date')->whereNotNull('baseline_start_date')->whereRaw('ABS(DATEDIFF(IFNULL(actual_finish_date, NOW()), actual_start_date)) - ABS(DATEDIFF(baseline_finish_date, baseline_start_date)) > 0');
            } elseif ($request->schedule_performance === 'Ahead of Schedule') {
                $query->whereNotNull('actual_start_date')->whereNotNull('baseline_finish_date')->whereNotNull('baseline_start_date')->whereRaw('ABS(DATEDIFF(IFNULL(actual_finish_date, NOW()), actual_start_date)) - ABS(DATEDIFF(baseline_finish_date, baseline_start_date)) < 0');
            } elseif ($request->schedule_performance === 'On Schedule') {
                $query->where(function ($q) {
                    $q->whereNotNull('actual_start_date')->whereNull('baseline_finish_date')
                        ->orWhereNotNull('actual_start_date')->whereNotNull('baseline_finish_date')->whereNotNull('baseline_start_date')->whereRaw('ABS(DATEDIFF(IFNULL(actual_finish_date, NOW()), actual_start_date)) - ABS(DATEDIFF(baseline_finish_date, baseline_start_date)) = 0');
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

        $businessUnits = BusinessUnit::active()->orderBy('name')->get(['id', 'name']);
        $sectors = Sector::active()
            ->when($businessUnitFilterId, fn ($sectors) => $sectors->where('business_unit_id', $businessUnitFilterId))
            ->orderBy('name')
            ->get(['id', 'business_unit_id', 'name']);

        return view('admin.projects.index', compact('projects', 'consultantTypes', 'businessUnits', 'sectors'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);
        $coordinators = \App\Models\User::where('role', 'user')->orderBy('name')->get(['id', 'name', 'email']);
        $user = request()->user();
        $businessUnits = $user->isAdmin()
            ? BusinessUnit::whereKey($user->business_unit_id)->with('activeSectors')->get()
            : BusinessUnit::active()->with('activeSectors')->get();
        $selectedBusinessUnitId = $user->isAdmin() ? $user->business_unit_id : $businessUnits->first()?->id;
        $sectors = Sector::active()->where('business_unit_id', $selectedBusinessUnitId)->get();

        if (request()->has('partial')) {
            return view('admin.projects.create-modal', compact('coordinators', 'businessUnits', 'sectors', 'selectedBusinessUnitId'));
        }

        return view('admin.projects.create', compact('coordinators', 'businessUnits', 'sectors', 'selectedBusinessUnitId'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Project::class);
        $businessUnitId = $request->user()->isAdmin()
            ? $request->user()->business_unit_id
            : $request->input('business_unit_id');

        $validated = $request->validate([
            'business_unit_id' => $request->user()->isAdmin()
                ? 'prohibited'
                : ['nullable', 'exists:business_units,id'],
            'sector_id' => ['nullable', Rule::exists('sectors', 'id')->where(fn ($query) => $query->where('business_unit_id', $businessUnitId))],
            'project_code' => 'nullable|string',
            'project_name' => 'required|string',
            'status' => 'nullable|in:in progress,suspended,completed',
            'project_type' => 'nullable|in:Building,Fit-Out,Infrastructure,Water & Energy,Mixed (Bui/Road),Mixed (Bui/Fit-Out)',
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
            'coordinator_ids' => ['sometimes', 'array'],
            'coordinator_ids.*' => ['integer', Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'user'))],
        ]);

        $validated['status'] = $validated['status'] ?? 'in progress';
        $validated['business_unit_id'] = $request->user()->isAdmin()
            ? $request->user()->business_unit_id
            : ($validated['business_unit_id'] ?? null);

        if (($validated['status'] ?? null) === 'in progress') {
            // An explicit In Progress selection clears completion data, even when
            // the edit modal submits an existing Actual Finish value.
            $validated['actual_finish_date'] = null;
        } elseif (! empty($validated['actual_finish_date'])) {
            $validated['status'] = 'completed';
        }

        DB::transaction(function () use ($validated) {
            $coordinatorIds = $validated['coordinator_ids'] ?? [];
            unset($validated['coordinator_ids']);

            $project = Project::create($validated);
            $project->coordinators()->sync($coordinatorIds);
        });

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function analytics(Request $request)
    {
        $dataDate = $request->filled('data_date') ? $request->data_date : null;
        $selectedProjectId = $request->filled('project_id') ? $request->project_id : null;
        $selectedSector = $request->filled('sector') ? $request->sector : null;

        $availableUpdates = \App\Models\ProjectProgressUpdate::query();
        ProjectOrganizationFilters::apply($availableUpdates, $request);
        if ($selectedProjectId) {
            $availableUpdates->where('project_id', $selectedProjectId);
        }

        $effectiveDataDate = $dataDate;
        if (! $effectiveDataDate || ! (clone $availableUpdates)->whereDate('data_date', $effectiveDataDate)->exists()) {
            $effectiveDataDate = (clone $availableUpdates)->whereNotNull('data_date')->max('data_date');
        }

        $progressQueryDataDate = \App\Models\ProjectProgressUpdate::query()
            ->whereDate('project_progress_updates.data_date', $effectiveDataDate);
        ProjectOrganizationFilters::apply($progressQueryDataDate, $request);

        if ($selectedProjectId) {
            $progressQueryDataDate->where('project_progress_updates.project_id', $selectedProjectId);
        }

        $projectIdsWithUpdates = $progressQueryDataDate->distinct('project_id')->pluck('project_id');

        $allProjectUpdatesQuery = \App\Models\ProjectProgressUpdate::query();
        ProjectOrganizationFilters::apply($allProjectUpdatesQuery, $request);
        $allProjectIdsWithUpdates = $allProjectUpdatesQuery->distinct('project_id')->pluck('project_id');

        $projectsQuery = Project::query();
        ProjectOrganizationFilters::apply($projectsQuery, $request, true);
        if ($selectedProjectId) {
            $projectsQuery->where('id', $selectedProjectId);
        } elseif ($selectedSector) {
            $projectsQuery->where('project_type', $selectedSector);
        }
        if ($allProjectIdsWithUpdates->isNotEmpty()) {
            $projectsQuery->whereIn('id', $allProjectIdsWithUpdates);
        }
        $projects = $projectsQuery->get();

        $totalPortfolioValue = $projects->sum(fn ($p) => (float) $p->total_project_value);
        $totalAllowableCost = $projects->sum(fn ($p) => (float) $p->total_allowable_cost);
        $totalCostAtCompletion = $projects->sum(fn ($p) => (float) $p->cost_at_completion);
        $totalVariations = $projects->sum(fn ($p) => (float) $p->variation);

        $costVariance = $totalPortfolioValue - $totalCostAtCompletion;
        $avgScheduleVariance = $projects->count() > 0 ? $projects->avg(fn ($p) => $p->schedule_variance) : 0;

        $projectTypes = Project::whereIn('id', $allProjectIdsWithUpdates)
            ->when($selectedSector, fn ($q) => $q->where('project_type', $selectedSector))
            ->when($selectedProjectId, fn ($q) => $q->where('id', $selectedProjectId))
            ->groupBy('project_type')
            ->selectRaw('project_type, count(*) as count')
            ->pluck('count', 'project_type');

        $clientValues = Project::whereIn('id', $allProjectIdsWithUpdates)
            ->when($selectedSector, fn ($q) => $q->where('project_type', $selectedSector))
            ->when($selectedProjectId, fn ($q) => $q->where('id', $selectedProjectId))
            ->whereNotNull('project_client')
            ->selectRaw('project_client, sum(contract_budget + variation + supplementary) as total_value')
            ->groupBy('project_client')
            ->pluck('total_value', 'project_client');

        $sectorValues = Project::whereIn('id', $allProjectIdsWithUpdates)
            ->when($selectedSector, fn ($q) => $q->where('project_type', $selectedSector))
            ->when($selectedProjectId, fn ($q) => $q->where('id', $selectedProjectId))
            ->whereNotNull('project_type')
            ->groupBy('project_type')
            ->selectRaw('project_type, count(*) as project_count')
            ->pluck('project_count', 'project_type');

        $puBaseScurve = function () use ($allProjectIdsWithUpdates) {
            return \App\Models\ProjectProgressUpdate::query()->whereIn('project_id', $allProjectIdsWithUpdates);
        };

        $puBase = function () use ($effectiveDataDate, $selectedProjectId, $selectedSector, $allProjectIdsWithUpdates) {
            $q = \App\Models\ProjectProgressUpdate::query()
                ->whereDate('project_progress_updates.data_date', $effectiveDataDate)
                ->whereIn('project_progress_updates.project_id', $allProjectIdsWithUpdates);
            if ($selectedProjectId) {
                $q->where('project_progress_updates.project_id', $selectedProjectId);
            } elseif ($selectedSector) {
                $q->whereHas('project', fn ($pq) => $pq->where('project_type', $selectedSector));
            }

            return $q;
        };

        $totalProjectsFromUpdates = $puBase()->select('project_id')->distinct()->count('project_id');

        $allProjectsWithUpdates = \App\Models\ProjectProgressUpdate::query()
            ->join('projects', 'project_progress_updates.project_id', '=', 'projects.id')
            ->whereIn('project_progress_updates.project_id', $allProjectIdsWithUpdates)
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
                $spiValues = $updates->filter(fn ($u) => $u->progress_planned > 0)->map(fn ($u) => ($u->progress_actual / $u->progress_planned) * 100);
                $cpiValues = $updates->filter(fn ($u) => $u->cost_actual > 0)->map(fn ($u) => $u->cost_planned / $u->cost_actual);
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
                    if ($latestSpi > $previous['spi']) {
                        $spiTrend = 'improving';
                    } elseif ($latestSpi < $previous['spi']) {
                        $spiTrend = 'declining';
                    }

                    if ($latestCpi > $previous['cpi']) {
                        $cpiTrend = 'improving';
                    } elseif ($latestCpi < $previous['cpi']) {
                        $cpiTrend = 'declining';
                    }
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
            $totalBudget = $latestProgressUpdates->sum(fn ($u) => $u->budget_amount);

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
        ) + ProjectOrganizationFilters::options($request));
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
        $this->authorize('update', $project);
        $coordinators = \App\Models\User::where('role', 'user')->orderBy('name')->get(['id', 'name', 'email']);
        $user = request()->user();
        $businessUnits = $user->isAdmin()
            ? BusinessUnit::whereKey($project->business_unit_id ?? $user->business_unit_id)->with('activeSectors')->get()
            : BusinessUnit::active()->with('activeSectors')->get();
        $selectedBusinessUnitId = $project->business_unit_id
            ?? ($user->isAdmin() ? $user->business_unit_id : $businessUnits->first()?->id);
        $sectors = Sector::active()->where('business_unit_id', $selectedBusinessUnitId)->get();

        if (request()->has('partial')) {
            return view('admin.projects.edit-modal', compact('project', 'coordinators', 'businessUnits', 'sectors', 'selectedBusinessUnitId'));
        }

        return view('admin.projects.edit', compact('project', 'coordinators', 'businessUnits', 'sectors', 'selectedBusinessUnitId'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        if ($request->exists('coordinator_ids') && ! $request->user()->isAdmin()) {
            abort(403, 'Only Admin can change project assignments.');
        }

        $user = $request->user();
        $businessUnitId = $user->isAdmin()
            ? ($project->business_unit_id ?? $user->business_unit_id)
            : ($user->isSuperadmin() ? $request->input('business_unit_id', $project->business_unit_id) : $project->business_unit_id);

        $validated = $request->validate([
            'business_unit_id' => $user->isAdmin() || $user->isSuperadmin()
                ? ($user->isAdmin() ? 'prohibited' : ['nullable', 'exists:business_units,id'])
                : 'prohibited',
            'sector_id' => [
                $user->isAdmin() || $user->isSuperadmin() ? 'nullable' : 'prohibited',
                Rule::exists('sectors', 'id')->where(fn ($query) => $query->where('business_unit_id', $businessUnitId)),
            ],
            'project_code' => 'nullable|string',
            'project_name' => 'required|string',
            'status' => 'nullable|in:in progress,suspended,completed',
            'project_type' => 'nullable|in:Building,Fit-Out,Infrastructure,Water & Energy,Mixed (Bui/Road),Mixed (Bui/Fit-Out)',
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
            'coordinator_ids' => ['sometimes', 'array'],
            'coordinator_ids.*' => ['integer', Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'user'))],
        ]);

        if (($validated['status'] ?? null) === 'in progress') {
            $validated['actual_finish_date'] = null;
        } elseif (! empty($validated['actual_finish_date'])) {
            $validated['status'] = 'completed';
        }

        if ($user->isAdmin()) {
            $validated['business_unit_id'] = $project->business_unit_id ?? $user->business_unit_id;
        } elseif ($user->isSuperadmin()) {
            $validated['business_unit_id'] = $validated['business_unit_id'] ?? $project->business_unit_id;
        } else {
            unset($validated['business_unit_id'], $validated['sector_id']);
        }

        $coordinatorIds = $validated['coordinator_ids'] ?? null;
        unset($validated['coordinator_ids']);

        DB::transaction(function () use ($project, $validated, $coordinatorIds, $request) {
            $project->update($validated);
            if ($request->user()->isAdmin() && $request->exists('coordinator_ids')) {
                $project->coordinators()->sync($coordinatorIds ?? []);
            }
        });

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function closeoutIndex(Request $request)
    {
        $query = Project::query();
        ProjectOrganizationFilters::apply($query, $request, true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhere('custom_id', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->paginate(15)->withQueryString();

        return view('admin.projects.closeout_index', compact('projects') + ProjectOrganizationFilters::options($request));
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

    public function riskIndex(Request $request)
    {
        $query = Project::query()->where('status', 'in progress')
            ->withCount('risks as total_risk')
            ->withCount(['risks as threat_risk' => fn ($q) => $q->where('risk_type', 'Threat')])
            ->withCount(['risks as opportunity_risk' => fn ($q) => $q->where('risk_type', 'Opportunity')])
            ->withCount(['risks as open_risk' => fn ($q) => $q->where('action_status', 'Risk')])
            ->withCount(['risks as in_progress_risk' => fn ($q) => $q->where('action_status', 'Issue')])
            ->withCount(['risks as closed_risk' => fn ($q) => $q->where('action_status', 'Resolved')]);
        ProjectOrganizationFilters::apply($query, $request, true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhere('project_client', 'like', "%{$search}%");
            });
        }

        if ($request->filled('project_id') && $request->project_id !== 'all') {
            $query->where('id', $request->project_id);
        }

        if ($request->filled('impact') && $request->impact !== 'all') {
            $scoreRanges = [
                'Low' => [1, 4],
                'Medium' => [5, 9],
                'High' => [10, 16],
                'Extreme' => [17, 25],
            ];
            if (isset($scoreRanges[$request->impact])) {
                [$minimum, $maximum] = $scoreRanges[$request->impact];
                $query->whereHas('risks', fn ($q) => $q->whereRaw('likelihood * severity between ? and ?', [$minimum, $maximum]));
            }
        }

        if ($request->filled('risk_type') && $request->risk_type !== 'all') {
            $query->whereHas('risks', fn ($q) => $q->where('risk_category', $request->risk_type));
        }

        if ($request->filled('risk_status') && $request->risk_status !== 'all') {
            $query->whereHas('risks', fn ($q) => $q->where('action_status', $request->risk_status));
        }

        $risks = $query->orderBy('project_code')->get()->toArray();
        $projectOptionsQuery = Project::query()->where('status', 'in progress')->orderBy('project_code');
        ProjectOrganizationFilters::apply($projectOptionsQuery, $request, true);
        $projects = $projectOptionsQuery->get()->toArray();
        $riskTypes = ['Contractual', 'Design', 'Environmental', 'Financial', 'Legal', 'Occupational Health & Safety (OHS)', 'Operational', 'Procurement', 'Quality'];

        return view('admin.projects.risk', compact('risks', 'projects', 'riskTypes') + ProjectOrganizationFilters::options($request));
    }

    public function riskManage(Project $project)
    {
        abort_unless(request()->user()->isAdmin() || request()->user()->isSuperadmin() || request()->user()->canManageProject($project), 403);

        if ($project->status !== 'in progress') {
            return redirect()->route('admin.projects.risk.index')->with('error', 'Risk management is only available for projects with "In Progress" status.');
        }

        $canManageProject = request()->user()->canManageProject($project);
        $risks = ProjectRisk::where('project_id', $project->id);

        if (request()->filled('search')) {
            $search = request('search');
            $risks->where(function ($q) use ($search) {
                $q->where('risk_name', 'like', "%{$search}%")
                    ->orWhere('risk_description', 'like', "%{$search}%")
                    ->orWhere('owner_name', 'like', "%{$search}%")
                    ->orWhere('risk_owner', 'like', "%{$search}%");
            });
        }

        if (request()->filled('risk_category') && request('risk_category') !== '') {
            $risks->where('risk_category', request('risk_category'));
        }

        if (request()->filled('risk_type') && request('risk_type') !== '') {
            $risks->where('risk_type', request('risk_type'));
        }

        if (request()->filled('risk_level') && request('risk_level') !== '') {
            $level = request('risk_level');
            match ($level) {
                'Extreme' => $risks->whereRaw('likelihood * severity >= 17'),
                'High' => $risks->whereRaw('likelihood * severity >= 10 AND likelihood * severity < 17'),
                'Medium' => $risks->whereRaw('likelihood * severity >= 5 AND likelihood * severity < 10'),
                'Low' => $risks->whereRaw('likelihood * severity < 5'),
                default => null,
            };
        }

        if (request()->filled('risk_urgency') && request('risk_urgency') !== '') {
            $risks->where('risk_urgency', request('risk_urgency'));
        }

        if (request()->filled('action_status') && request('action_status') !== '') {
            $risks->where('action_status', request('action_status'));
        }

        $risks = $risks->latest()->paginate(15)->withQueryString();
        $stats = [
            'total' => ProjectRisk::where('project_id', $project->id)->count(),
            'risk' => ProjectRisk::where('project_id', $project->id)->where('action_status', 'Risk')->count(),
            'issue' => ProjectRisk::where('project_id', $project->id)->where('action_status', 'Issue')->count(),
            'resolved' => ProjectRisk::where('project_id', $project->id)->where('action_status', 'Resolved')->count(),
            'extreme' => ProjectRisk::where('project_id', $project->id)->whereRaw('likelihood * severity >= 17')->count(),
            'high' => ProjectRisk::where('project_id', $project->id)->whereRaw('likelihood * severity >= 10 AND likelihood * severity < 17')->count(),
            'medium' => ProjectRisk::where('project_id', $project->id)->whereRaw('likelihood * severity >= 5 AND likelihood * severity < 10')->count(),
            'low' => ProjectRisk::where('project_id', $project->id)->whereRaw('likelihood * severity < 5')->count(),
        ];

        $riskCategories = array_merge(
            ['Contractual', 'Design', 'Environmental', 'Financial', 'Legal', 'Occupational Health & Safety (OHS)', 'Operational', 'Procurement', 'Quality'],
            ProjectRiskPool::availableCategories()
        );

        return view('admin.projects.risk.manage', compact('project', 'risks', 'stats', 'canManageProject', 'riskCategories'));
    }

    public function riskCreateModal(Project $project)
    {
        abort_unless(auth()->user()->canManageProject($project), 403);

        $poolTypes = ProjectRiskPool::RISK_TYPES;
        $eotOptions = $this->riskEotOptions();

        return view('admin.projects.risk.create-modal', compact('project', 'poolTypes', 'eotOptions'))->render();
    }

    /**
     * Return type-specific categories or the risks for a selected type/category.
     */
    public function poolRisksByCategory(Request $request, Project $project)
    {
        abort_unless(auth()->user()->canManageProject($project), 403);

        $validated = $request->validate([
            'risk_type' => ['required', Rule::in(ProjectRiskPool::RISK_TYPES)],
            'category' => ['nullable', 'string', 'max:255'],
        ]);
        $query = ProjectRiskPool::query()->where('risk_type', $validated['risk_type']);
        if (empty($validated['category'])) {
            $eligibleCategoryNames = RiskCategory::query()
                ->active()
                ->where(function ($categoryQuery) use ($validated) {
                    $categoryQuery->whereNull('risk_type')
                        ->orWhere('risk_type', $validated['risk_type']);
                })
                ->pluck('name');
            $assignedCategories = RiskCategory::query()
                ->active()
                ->where('risk_type', $validated['risk_type'])
                ->ordered()
                ->pluck('name')
                ->all();
            $poolCategories = ProjectRiskPool::query()
                ->where('risk_type', $validated['risk_type'])
                ->whereIn('risk_category', $eligibleCategoryNames)
                ->distinct()
                ->pluck('risk_category')
                ->all();

            $categoryNames = collect($assignedCategories)
                ->merge($poolCategories)
                ->unique()
                ->values();

            return response()->json(RiskCategory::query()
                ->active()
                ->whereIn('name', $categoryNames)
                ->ordered()
                ->pluck('name')
                ->values());
        }

        $category = RiskCategory::query()
            ->active()
            ->where('name', $validated['category'])
            ->first();
        abort_if(
            ! $category
                || ($category->risk_type !== null && $category->risk_type !== $validated['risk_type']),
            422,
            'The selected category is not available for this risk type.'
        );

        $risks = $query->where('risk_category', $validated['category'])->ordered()->get();

        return response()->json($risks->map(fn ($r) => [
            'risk_id' => $r->risk_id,
            'specific_risk' => $r->specific_risk,
            'detailed_risk_explanation' => $r->detailed_risk_explanation,
            'impact_on_timeline' => $r->impact_on_timeline,
            'impact_on_timeline_description' => $r->impact_on_timeline_description,
            'impact_on_cost' => $r->impact_on_cost,
            'impact_on_cost_description' => $r->impact_on_cost_description,
            'overall_impact_level' => $r->overall_impact_level,
            'impact_on_quality_description' => $r->impact_on_quality_description,
            'risk_category' => $r->risk_category,
            'risk_type' => $r->risk_type,
        ])->values());
    }

    public function riskStore(Request $request, Project $project)
    {
        abort_unless($request->user()->canManageProject($project), 403);
        abort_unless($project->status === 'in progress', 403);

        $validated = $request->validate([
            'risk_category' => ['nullable', 'string', 'max:255'],
            'pool_risk_category' => ['nullable', 'string', 'max:255', 'required_with:pool_risk_id'],
            'pool_risk_id' => [
                'nullable',
                'string',
                'max:255',
                Rule::exists('project_risk_pools', 'risk_id')
                    ->where('risk_type', $request->input('risk_type'))
                    ->where('risk_category', $request->input('pool_risk_category')),
            ],
            'eot_category_id' => ['nullable', 'required_if:intent_to_claim,1', 'integer', Rule::exists('eot_categories', 'id')],
            'eot_entry_id' => [
                'nullable',
                'required_if:intent_to_claim,1',
                'integer',
                Rule::exists('eot_entries', 'id')->where('eot_category_id', $request->input('eot_category_id')),
            ],
            'detail_reason_for_eot' => 'nullable|string',
            'project_case_event_description' => 'nullable|string',
            'reference_contract_clause_no' => 'nullable|string|max:255',
            'affected_activities' => 'nullable|string',
            'itc_notification_status' => ['nullable', Rule::in(['Submitted', 'Not Submitted'])],
            'itc_notification_date' => ['nullable', Rule::requiredIf(fn () => $request->boolean('intent_to_claim') && $request->input('itc_notification_status') === 'Submitted'), 'date'],
            'eot_status' => ['nullable', Rule::in(['Submitted', 'Not Submitted', 'Agreed', 'Rejected', 'Approved'])],
            'eot_submitted_date' => ['nullable', Rule::requiredIf(fn () => $request->boolean('intent_to_claim') && $request->input('eot_status') === 'Submitted'), 'date'],
            'eot_response_date' => 'nullable|date',
            'eot_requested_days' => 'nullable|integer|min:0',
            'eot_approved_days' => 'nullable|integer|min:0',
            'activity' => 'required|string',
            'risk_type' => ['required', Rule::in(ProjectRiskPool::RISK_TYPES)],
            'risk_name' => 'nullable|string|max:255',
            'risk_description' => 'required|string',
            'likelihood' => 'required|integer|between:1,5',
            'severity' => 'required|integer|between:1,5',
            'intent_to_claim' => 'required|boolean',
            'most_likely_cost_impact' => 'nullable|numeric|min:0',
            'most_likely_time_impact' => 'nullable|integer|min:0',
            'most_likely_quality_impact_description' => 'nullable|string',
            'time_impact_unit' => ['nullable', Rule::in(['Day', 'Week', 'Month'])],
            'resolution_by' => 'nullable|string|max:255',
            'risk_frequency' => ['nullable', Rule::in(['One Time', 'Fixed Duration', 'Recurring'])],
            'impact_date' => 'nullable|date',
            'risk_urgency' => ['nullable', Rule::in(['Very Urgent', 'Urgent', 'Moderate', 'Low'])],
            'existing_control' => 'nullable|string',
            'residual_risk' => 'nullable|string',
            'response_strategy' => ['nullable', Rule::in(ProjectRisk::RESPONSE_STRATEGIES[$request->input('risk_type')] ?? [])],
            'mitigation_plan' => 'nullable|string',
            'contingency_plan' => 'nullable|string',
            'owner_name' => 'nullable|string|max:255',
            'action_owner' => 'nullable|string|max:255',
            'planned_action_date' => 'nullable|date',
            'last_review_date' => 'nullable|date',
            'tracking_comments' => 'nullable|string',
            'action_status' => ['required', Rule::in(ProjectRisk::ACTION_STATUSES)],
            'actual_action_date' => 'nullable|date',
            'materialized_date' => 'nullable|date',
            'exposure_unit' => ['nullable', Rule::in(['Day', 'Week', 'Month'])],
            'recommendation' => 'nullable|string',
        ]);

        $validated['project_id'] = $project->id;
        $validated['created_by'] = $request->user()->id;
        $validated['date_last_updated'] = now()->toDateString();
        $validated['risk_name'] = ! empty($validated['pool_risk_id'])
            ? ProjectRiskPool::query()
                ->where('risk_id', $validated['pool_risk_id'])
                ->where('risk_type', $validated['risk_type'])
                ->where('risk_category', $validated['pool_risk_category'])
                ->value('specific_risk')
            : ($validated['risk_name'] ?: \Illuminate\Support\Str::limit($validated['activity'], 255));
        $validated['exposure'] = ProjectRisk::calculateExposureDays(
            $validated['materialized_date'] ?? null,
            $validated['actual_action_date'] ?? null
        );
        if (! $validated['intent_to_claim']) {
            $validated['eot_entry_id'] = null;
            $validated = $this->clearEotClaimTracking($validated);
        } else {
            $validated = $this->clearUnavailableEotDates($validated);
        }
        unset($validated['eot_category_id']);
        ProjectRisk::create($validated);

        return redirect()->route('admin.projects.risk.manage', $project)->with('success', 'Risk created successfully.');
    }

    public function riskViewModal(ProjectRisk $risk)
    {
        $risk->load('eotEntry.baseOfRisk');
        $poolRisk = $risk->pool_risk_id
            ? ProjectRiskPool::where('risk_id', $risk->pool_risk_id)->first()
            : null;

        return view('admin.projects.risk.view-modal', compact('risk', 'poolRisk'))->render();
    }

    public function riskEditModal(ProjectRisk $risk)
    {
        $this->authorizeRiskMutation($risk);
        $poolTypes = ProjectRiskPool::RISK_TYPES;
        $eotOptions = $this->riskEotOptions($risk);
        $risk->load('eotEntry');
        $poolRisk = $risk->pool_risk_id
            ? ProjectRiskPool::where('risk_id', $risk->pool_risk_id)->first()
            : null;

        return view('admin.projects.risk.edit-modal', compact('risk', 'poolTypes', 'eotOptions', 'poolRisk'))->render();
    }

    public function riskUpdate(Request $request, ProjectRisk $risk)
    {
        $this->authorizeRiskMutation($risk);

        $validated = $request->validate([
            'risk_category' => ['nullable', 'string', 'max:255'],
            'pool_risk_category' => ['nullable', 'string', 'max:255'],
            'pool_risk_id' => [
                'nullable',
                'string',
                'max:255',
                Rule::exists('project_risk_pools', 'risk_id')
                    ->where('risk_type', $request->input('risk_type'))
                    ->where('risk_category', $request->input('pool_risk_category')),
            ],
            'eot_category_id' => ['nullable', 'required_if:intent_to_claim,1', 'integer', Rule::exists('eot_categories', 'id')],
            'eot_entry_id' => [
                'nullable',
                'required_if:intent_to_claim,1',
                'integer',
                Rule::exists('eot_entries', 'id')->where('eot_category_id', $request->input('eot_category_id')),
            ],
            'detail_reason_for_eot' => 'nullable|string',
            'project_case_event_description' => 'nullable|string',
            'reference_contract_clause_no' => 'nullable|string|max:255',
            'affected_activities' => 'nullable|string',
            'itc_notification_status' => ['nullable', Rule::in(['Submitted', 'Not Submitted'])],
            'itc_notification_date' => ['nullable', Rule::requiredIf(fn () => $request->boolean('intent_to_claim') && $request->input('itc_notification_status') === 'Submitted'), 'date'],
            'eot_status' => ['nullable', Rule::in(['Submitted', 'Not Submitted', 'Agreed', 'Rejected', 'Approved'])],
            'eot_submitted_date' => ['nullable', Rule::requiredIf(fn () => $request->boolean('intent_to_claim') && $request->input('eot_status') === 'Submitted'), 'date'],
            'eot_response_date' => 'nullable|date',
            'eot_requested_days' => 'nullable|integer|min:0',
            'eot_approved_days' => 'nullable|integer|min:0',
            'activity' => 'required|string',
            'risk_type' => ['required', Rule::in(ProjectRiskPool::RISK_TYPES)],
            'risk_name' => 'nullable|string|max:255',
            'risk_description' => 'required|string',
            'likelihood' => 'required|integer|between:1,5',
            'severity' => 'required|integer|between:1,5',
            'intent_to_claim' => 'required|boolean',
            'most_likely_cost_impact' => 'nullable|numeric|min:0',
            'most_likely_time_impact' => 'nullable|integer|min:0',
            'most_likely_quality_impact_description' => 'nullable|string',
            'time_impact_unit' => ['nullable', Rule::in(['Day', 'Week', 'Month'])],
            'resolution_by' => 'nullable|string|max:255',
            'risk_frequency' => ['nullable', Rule::in(['One Time', 'Fixed Duration', 'Recurring'])],
            'impact_date' => 'nullable|date',
            'risk_urgency' => ['nullable', Rule::in(['Very Urgent', 'Urgent', 'Moderate', 'Low'])],
            'existing_control' => 'nullable|string',
            'residual_risk' => 'nullable|string',
            'response_strategy' => ['nullable', Rule::in(ProjectRisk::RESPONSE_STRATEGIES[$request->input('risk_type')] ?? [])],
            'mitigation_plan' => 'nullable|string',
            'contingency_plan' => 'nullable|string',
            'owner_name' => 'nullable|string|max:255',
            'action_owner' => 'nullable|string|max:255',
            'planned_action_date' => 'nullable|date',
            'last_review_date' => 'nullable|date',
            'tracking_comments' => 'nullable|string',
            'action_status' => ['required', Rule::in(ProjectRisk::ACTION_STATUSES)],
            'actual_action_date' => 'nullable|date',
            'materialized_date' => 'nullable|date',
            'exposure_unit' => ['nullable', Rule::in(['Day', 'Week', 'Month'])],
            'recommendation' => 'nullable|string',
        ]);

        $validated['date_last_updated'] = now()->toDateString();
        $validated['risk_name'] = ! empty($validated['pool_risk_id'])
            ? ProjectRiskPool::query()
                ->where('risk_id', $validated['pool_risk_id'])
                ->where('risk_type', $validated['risk_type'])
                ->where('risk_category', $validated['pool_risk_category'])
                ->value('specific_risk')
            : ($validated['risk_name'] ?: $risk->risk_name);
        $validated['exposure'] = ProjectRisk::calculateExposureDays(
            $validated['materialized_date'] ?? null,
            $validated['actual_action_date'] ?? null
        );
        if (! $validated['intent_to_claim']) {
            $validated['eot_entry_id'] = null;
            $validated = $this->clearEotClaimTracking($validated);
        } else {
            $validated = $this->clearUnavailableEotDates($validated);
        }
        unset($validated['eot_category_id']);
        $risk->update($validated);

        return redirect()->route('admin.projects.risk.manage', $risk->project)->with('success', 'Risk updated successfully.');
    }

    private function clearEotClaimTracking(array $validated): array
    {
        foreach ([
            'detail_reason_for_eot',
            'project_case_event_description',
            'reference_contract_clause_no',
            'affected_activities',
            'itc_notification_status',
            'itc_notification_date',
            'eot_status',
            'eot_submitted_date',
            'eot_response_date',
            'eot_requested_days',
            'eot_approved_days',
        ] as $field) {
            $validated[$field] = null;
        }

        return $validated;
    }

    private function clearUnavailableEotDates(array $validated): array
    {
        if (($validated['itc_notification_status'] ?? null) !== 'Submitted') {
            $validated['itc_notification_date'] = null;
        }
        if (($validated['eot_status'] ?? null) !== 'Submitted') {
            $validated['eot_submitted_date'] = null;
        }

        return $validated;
    }

    private function riskEotOptions(?ProjectRisk $risk = null): array
    {
        $categories = EotCategory::active()->ordered()->get(['id', 'name']);
        $categoryIds = $categories->pluck('id');
        $entries = EotEntry::query()
            ->whereIn('eot_category_id', $categoryIds)
            ->orderBy('eot_category_id')
            ->orderBy('eot_id')
            ->get(['id', 'eot_category_id', 'eot_id', 'detail_reason']);

        if ($risk?->eot_entry_id && ! $entries->contains('id', $risk->eot_entry_id)) {
            $selectedEntry = EotEntry::with('baseOfRisk')->find($risk->eot_entry_id);
            if ($selectedEntry) {
                if (! $categories->contains('id', $selectedEntry->eot_category_id)) {
                    $categories->push($selectedEntry->baseOfRisk);
                }
                $entries->push($selectedEntry->only(['id', 'eot_category_id', 'eot_id', 'detail_reason']));
            }
        }

        return [
            'categories' => $categories->values(),
            'entries' => $entries->values(),
        ];
    }

    public function riskDestroy(ProjectRisk $risk)
    {
        abort_unless((auth()->user()->isAdmin() || auth()->user()->isSuperadmin()) && auth()->user()->canManageProject($risk->project), 403);
        $project = $risk->project;
        $risk->delete();

        return redirect()->route('admin.projects.risk.manage', $project)->with('success', 'Risk deleted successfully.');
    }

    private function authorizeRiskMutation(ProjectRisk $risk): void
    {
        $user = auth()->user();

        abort_unless(
            ($user->isAdmin() || $user->isSuperadmin()) && $risk->project && $user->canManageProject($risk->project)
            || ($user->isUser()
                && $risk->project
                && $user->canManageProject($risk->project)),
            403
        );
    }

    public function search(Request $request)
    {
        $search = $request->get('q', '');
        $query = Project::query()
            ->where('status', 'active')
            ->where(function ($query) use ($search) {
                $query->where('project_name', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhere('custom_id', 'like', "%{$search}%");
            });

        if ($request->user()->isUser()) {
            $query->whereHas('coordinators', fn ($coordinators) => $coordinators->whereKey($request->user()->id));
        }

        $projects = $query->orderBy('project_name')
            ->limit(20)
            ->get(['id', 'project_code', 'project_name']);

        return response()->json($projects->map(function ($project) {
            return [
                'id' => $project->id,
                'text' => $project->project_code.' - '.$project->project_name,
                'project_code' => $project->project_code,
                'project_name' => $project->project_name,
            ];
        }));
    }

    public function toggleStatus(Project $project)
    {
        $this->authorize('update', $project);

        $statuses = ['in progress', 'completed', 'suspended'];
        $currentIndex = array_search($project->status, $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);
        $newStatus = $statuses[$nextIndex];
        $project->update(['status' => $newStatus]);

        return response()->json(['success' => true, 'status' => $newStatus]);
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

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
            'code' => sprintf('%s-%03d', $prefix, $nextSequence),
        ]);
    }

    public function issuesIndex(Request $request)
    {
        $query = Issue::query()->with('project');
        ProjectOrganizationFilters::apply($query, $request);

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
            $query->status($request->status);
        }

        if ($request->filled('impact') && $request->impact !== 'all') {
            $query->where('impact', $request->impact);
        }

        if ($request->filled('issue_type') && $request->issue_type !== 'all') {
            $query->where('issue_type', $request->issue_type);
        }

        if ($request->has('partial') && $request->get('action') === 'create') {
            $projects = Project::orderBy('project_name')->get(['id', 'project_code', 'project_name', 'project_type', 'consultancy_sector']);

            return view('admin.projects.issues.create-modal', compact('projects'))->render();
        }

        $issues = $query->orderByDesc('raised_date')->paginate(15)->withQueryString();
        $projectOptionsQuery = Project::orderBy('project_name');
        ProjectOrganizationFilters::apply($projectOptionsQuery, $request, true);
        $projects = $projectOptionsQuery->get(['id', 'project_code', 'project_name', 'project_type', 'consultancy_sector']);
        $issueTypes = Issue::query()->select('issue_type')->distinct()->orderBy('issue_type')->pluck('issue_type');

        return view('admin.projects.issues', compact('issues', 'projects', 'issueTypes') + ProjectOrganizationFilters::options($request));
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
        ProjectOrganizationFilters::apply($query, $request);

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
            $query->status($request->status);
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
        $raised = $allIssues->where('status', 'Raised')->count();
        $open = $allIssues->where('status', 'Open')->count();
        $closed = $allIssues->where('status', 'Closed')->count();

        $today = Carbon::today();
        $overdue = $allIssues->filter(function ($issue) use ($today) {
            return $issue->planned_resolution_date
                && $issue->planned_resolution_date->lt($today)
                && $issue->status !== 'Closed';
        })->count();

        $notClosedIssues = $allIssues->filter(fn ($issue) => $issue->status !== 'Closed');
        $highPriority = $notClosedIssues->filter(fn ($issue) => $issue->days_left !== null && $issue->days_left >= 0 && $issue->days_left < 7)->count();
        $mediumPriority = $notClosedIssues->filter(fn ($issue) => $issue->days_left !== null && $issue->days_left >= 7 && $issue->days_left < 14)->count();
        $lowPriority = $notClosedIssues->filter(fn ($issue) => $issue->days_left !== null && $issue->days_left >= 14)->count();

        $highImpact = $allIssues->where('impact', 'High')->count();
        $mediumImpact = $allIssues->where('impact', 'Medium')->count();
        $lowImpact = $allIssues->where('impact', 'Low')->count();

        $statusCounts = [
            'Raised' => $raised,
            'Open' => $open,
            'Closed' => $closed,
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

        $p50 = ! empty($resolutionTimesArray) ? $resolutionTimesArray[max(0, floor((count($resolutionTimesArray) - 1) * 0.5))] : 0;
        $p90 = ! empty($resolutionTimesArray) ? $resolutionTimesArray[max(0, floor((count($resolutionTimesArray) - 1) * 0.9))] : 0;

        $projectOptionsQuery = Project::orderBy('project_name');
        ProjectOrganizationFilters::apply($projectOptionsQuery, $request, true);
        $projects = $projectOptionsQuery->get(['id', 'project_code', 'project_name']);

        $exportIssues = $allIssues->map(function ($issue) {
            return [
                'project' => $issue->project?->project_code ?? $issue->project_code ?? '',
                'issue' => $issue->issue ?? '',
                'type' => $issue->issue_type ?? '',
                'impact' => $issue->impact ?? '',
                'status' => $issue->status ?? '',
                'raised_by' => $issue->raised_by ?? '',
                'raised_date' => $issue->raised_date?->format('Y-m-d') ?? '',
                'planned_resolution_date' => $issue->planned_resolution_date?->format('Y-m-d') ?? '',
                'action_by' => $issue->action_by ?? '',
            ];
        })->values();

        $issues = $query->orderByDesc('raised_date')->paginate(15)->withQueryString();

        return view('admin.projects.issues-report', compact(
            'issues',
            'projects',
            'total',
            'open',
            'closed',
            'overdue',
            'highPriority',
            'mediumPriority',
            'lowPriority',
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
            'exportIssues',
        ) + ProjectOrganizationFilters::options($request));
    }
}
