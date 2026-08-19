<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function data(Request $request)
    {
        try {
            return $this->buildDashboardData($request);
        } catch (\Throwable $e) {
            \Log::error('Dashboard data error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Failed to load dashboard data: ' . $e->getMessage(),
                'projects' => [],
                'kpis' => [],
                'executive_kpis' => [],
                'filters' => [],
                'total' => 0,
                'analytics' => [],
                'comparative_analytics' => [],
            ], 200);
        }
    }

    private function buildDashboardData(Request $request)
    {
        $query = Project::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhere('custom_id', 'like', "%{$search}%")
                    ->orWhere('project_client', 'like', "%{$search}%")
                    ->orWhere('project_manager', 'like', "%{$search}%");
            });
        }

        if ($request->filled('project_type') && $request->project_type !== 'all') {
            $query->where('project_type', $request->project_type);
        }

        if ($request->filled('project_status') && $request->project_status !== 'all') {
            $query->where('closing_status', $request->project_status);
        }

        if ($request->filled('client') && $request->client !== 'all') {
            $query->where('project_client', $request->client);
        }

        if ($request->filled('sector') && $request->sector !== 'all') {
            $query->where('project_type', $request->sector);
        }

        if ($request->filled('lifecycle_status') && $request->lifecycle_status !== 'all') {
            $query->where('status', $request->lifecycle_status);
        }

        if ($request->filled('delivery_method') && $request->delivery_method !== 'all') {
            $query->where('delivery_method', $request->delivery_method);
        }

        if ($request->filled('consultant_type') && $request->consultant_type !== 'all') {
            $query->where('consultancy_sector', $request->consultant_type);
        }

        if ($request->filled('variation_type') && $request->variation_type !== 'all') {
            if ($request->variation_type === 'addition') {
                $query->where('variation', '>', 0);
            } elseif ($request->variation_type === 'omission') {
                $query->where('variation', '<', 0);
            } else {
                $query->where('variation', 0);
            }
        }

        $projects = $query->get();

        $filteredProjects = $projects->map(function ($project) {
            $scheduleStatus = null;
            $schedulePerformancePercent = 0;
            $baselineDuration = 0;
            $actualDuration = 0;
            $scheduleVariance = 0;

            if (!$project->actual_start_date) {
                $scheduleStatus = 'Not Started';
                $schedulePerformancePercent = 0;
            } elseif ($project->baseline_start_date && $project->baseline_finish_date) {
                $baselineDuration = abs($project->baseline_finish_date->diffInDays($project->baseline_start_date));
                $actualFinish = $project->actual_finish_date ?: now();
                $actualDuration = abs($actualFinish->diffInDays($project->actual_start_date));
                $scheduleVariance = $actualDuration - $baselineDuration;

                if ($scheduleVariance > 0) {
                    $scheduleStatus = 'Behind Schedule';
                } elseif ($scheduleVariance < 0) {
                    $scheduleStatus = 'Ahead of Schedule';
                } else {
                    $scheduleStatus = 'On Schedule';
                }

                if ($baselineDuration > 0) {
                    $remainingDays = $actualFinish->diffInDays($project->baseline_finish_date);
                    $schedulePerformancePercent = round(($remainingDays / $baselineDuration) * 100, 1);
                }
            } else {
                $scheduleStatus = 'On Schedule';
            }

            $revisedBudgetAmount = ($project->contract_budget ?? 0) + ($project->variation ?? 0) + ($project->supplementary ?? 0);
            $revisedBudget = $revisedBudgetAmount;
            $contractBudget = (float) ($project->contract_budget ?? 0);
            $budgetAtCompletion = (float) ($project->budget_at_completion ?? 0);
            $signedBudgetVariance = $revisedBudget - $budgetAtCompletion;
            $budgetVariance = $revisedBudget - $budgetAtCompletion;
            $budgetPerformance = $budgetVariance > 0 ? 'Above Budget' : ($budgetVariance < 0 ? 'Under Budget' : 'On Budget');
            $budgetIndexPercent = $revisedBudget > 0 ? round(($budgetVariance / $revisedBudget) * 100, 1) : 0;

            $allowableCost = (float) ($project->total_allowable_cost ?? 0);
            $costAtCompletion = (float) ($project->cost_at_completion ?? 0);
            $costVariance = $allowableCost - $costAtCompletion;
            $costEacIndexPercent = $allowableCost > 0 ? round(($costVariance / $allowableCost) * 100, 1) : 0;

            if ($costVariance < 0) {
                $costPerformance = 'Above Allowable Cost';
            } elseif ($costVariance > 0) {
                $costPerformance = 'Below Allowable Cost';
            } else {
                $costPerformance = 'Within Allowable Cost';
            }

            if ($scheduleStatus !== 'Behind Schedule' && $budgetPerformance !== 'Above Budget' && $scheduleStatus !== 'Not Started') {
                $overallPerformance = 'Good';
            } elseif ($scheduleStatus === 'Behind Schedule' || $budgetPerformance === 'Above Budget' || $scheduleStatus === 'Not Started') {
                $overallPerformance = 'Attention!';
            } else {
                $overallPerformance = 'Check Data';
            }

            return [
                'id' => $project->id,
                'project_code' => $project->project_code,
                'project_name' => $project->project_name,
                'project_type' => $project->project_type,
                'project_client' => $project->project_client,
                'consultancy_sector' => $project->consultancy_sector,
                'project_manager' => $project->project_manager,
                'closing_status' => $project->closing_status,
                'status' => $project->status,
                'delivery_method' => $project->delivery_method,
                'variation' => (float) ($project->variation ?? 0),
                'contract_budget' => $contractBudget,
                'revised_budget' => $revisedBudget,
                'total_allowable_cost' => $allowableCost,
                'cost_at_completion' => $costAtCompletion,
                'baseline_start_date' => $project->baseline_start_date?->format('Y-m-d'),
                'baseline_finish_date' => $project->baseline_finish_date?->format('Y-m-d'),
                'actual_start_date' => $project->actual_start_date?->format('Y-m-d'),
                'actual_finish_date' => $project->actual_finish_date?->format('Y-m-d'),
                'schedule_performance_percent' => $schedulePerformancePercent,
                'schedule_status' => $scheduleStatus,
                'schedule_variance_days' => $scheduleVariance,
                'actual_duration' => $actualDuration,
                'baseline_duration' => $baselineDuration,
                'budget_index_percent' => $budgetIndexPercent,
                'budget_performance' => $budgetPerformance,
                'budget_variance' => $signedBudgetVariance,
                'cost_performance_percent' => $costEacIndexPercent,
                'cost_performance' => $costPerformance,
                'cost_variance' => $costVariance,
                'overall_performance' => $overallPerformance,
            ];
        });

        $filteredProjects = $filteredProjects->filter(function ($project) use ($request) {
            if ($request->filled('schedule_filter') && $request->schedule_filter !== 'all') {
                if ($project['schedule_status'] !== $request->schedule_filter) {
                    return false;
                }
            }
            if ($request->filled('cost_filter') && $request->cost_filter !== 'all') {
                if ($project['cost_performance'] !== $request->cost_filter) {
                    return false;
                }
            }
            return true;
        });

        $totalProjects = $filteredProjects->count();

        $scheduleAhead = $filteredProjects->where('schedule_status', 'Ahead of Schedule')->count();
        $scheduleOn = $filteredProjects->where('schedule_status', 'On Schedule')->count();
        $scheduleBehind = $filteredProjects->where('schedule_status', 'Behind Schedule')->count();

        $budgetUnder = $filteredProjects->where('budget_performance', 'Under Budget')->count();
        $budgetOn = $filteredProjects->where('budget_performance', 'On Budget')->count();
        $budgetAbove = $filteredProjects->where('budget_performance', 'Above Budget')->count();

        $costBelow = $filteredProjects->where('cost_performance', 'Below Allowable Cost')->count();
        $costWithin = $filteredProjects->where('cost_performance', 'Within Allowable Cost')->count();
        $costAbove = $filteredProjects->where('cost_performance', 'Above Allowable Cost')->count();

        $overallGood = $filteredProjects->where('overall_performance', 'Good')->count();
        $overallAttention = $filteredProjects->where('overall_performance', 'Attention!')->count();

        $kpis = [
            'schedule' => [
                'ahead' => ['count' => $scheduleAhead, 'percent' => $totalProjects > 0 ? round(($scheduleAhead / $totalProjects) * 100) : 0],
                'on_schedule' => ['count' => $scheduleOn, 'percent' => $totalProjects > 0 ? round(($scheduleOn / $totalProjects) * 100) : 0],
                'behind' => ['count' => $scheduleBehind, 'percent' => $totalProjects > 0 ? round(($scheduleBehind / $totalProjects) * 100) : 0],
            ],
            'budget' => [
                'under' => ['count' => $budgetUnder, 'percent' => $totalProjects > 0 ? round(($budgetUnder / $totalProjects) * 100) : 0],
                'on_budget' => ['count' => $budgetOn, 'percent' => $totalProjects > 0 ? round(($budgetOn / $totalProjects) * 100) : 0],
                'above' => ['count' => $budgetAbove, 'percent' => $totalProjects > 0 ? round(($budgetAbove / $totalProjects) * 100) : 0],
            ],
            'cost' => [
                'below' => ['count' => $costBelow, 'percent' => $totalProjects > 0 ? round(($costBelow / $totalProjects) * 100) : 0],
                'within' => ['count' => $costWithin, 'percent' => $totalProjects > 0 ? round(($costWithin / $totalProjects) * 100) : 0],
                'above' => ['count' => $costAbove, 'percent' => $totalProjects > 0 ? round(($costAbove / $totalProjects) * 100) : 0],
            ],
            'overall' => [
                'good' => ['count' => $overallGood, 'percent' => $totalProjects > 0 ? round(($overallGood / $totalProjects) * 100) : 0],
                'attention' => ['count' => $overallAttention, 'percent' => $totalProjects > 0 ? round(($overallAttention / $totalProjects) * 100) : 0],
            ],
        ];

        $filters = [
            'project_types' => ['Building', 'Fit-Out', 'Infrastructure', 'Water & Energy'],
            'project_statuses' => ['Not Completed', 'PPA Received', 'PA Received', 'FA Received', 'Snag / Di-Snag', 'Waiting for PA'],
            'clients' => Project::whereNotNull('project_client')->groupBy('project_client')->pluck('project_client')->sort()->values()->all(),
            'sectors' => ['Building', 'Fit-Out', 'Infrastructure', 'Water & Energy'],
            'consultant_types' => ['Government', 'Private', 'Client / Engineering Team'],
            'delivery_methods' => ['DB', 'DBB'],
            'lifecycle_statuses' => ['in progress', 'completed', 'suspended'],
        ];

        $analytics = [
            'project_status' => [
                'completed' => $filteredProjects->where('status', 'completed')->count(),
                'in_progress' => $filteredProjects->where('status', 'in progress')->count(),
                'suspended' => $filteredProjects->where('status', 'suspended')->count(),
                'total' => $totalProjects,
            ],
            'schedule_performance' => [
                'ahead' => $filteredProjects->where('status', 'completed')->where('schedule_status', 'Ahead of Schedule')->count(),
                'on_schedule' => $filteredProjects->where('status', 'completed')->where('schedule_status', 'On Schedule')->count(),
                'behind' => $filteredProjects->where('status', 'completed')->where('schedule_status', 'Behind Schedule')->count(),
                'total' => $filteredProjects->where('status', 'completed')->count(),
            ],
            'delivery_method' => [
                'DB' => $filteredProjects->where('status', 'completed')->where('delivery_method', 'DB')->count(),
                'DBB' => $filteredProjects->where('status', 'completed')->where('delivery_method', 'DBB')->count(),
                'total' => $filteredProjects->where('status', 'completed')->count(),
            ],
            'contract_variation' => [
                'addition' => $filteredProjects->where('variation', '>', 0)->count(),
                'omission' => $filteredProjects->where('variation', '<', 0)->count(),
                'no_variation' => $filteredProjects->where('variation', 0)->count(),
                'total' => $totalProjects,
            ],
            'consultancy_sector' => [
                'private' => $filteredProjects->where('status', 'completed')->where('consultancy_sector', 'Private')->count(),
                'client_engineering' => $filteredProjects->where('status', 'completed')->where('consultancy_sector', 'Client / Engineering Team')->count(),
                'government' => $filteredProjects->where('status', 'completed')->where('consultancy_sector', 'Government')->count(),
                'total' => $filteredProjects->where('status', 'completed')->count(),
            ],
            'cost_performance' => [
                'below' => $filteredProjects->where('status', 'completed')->where('cost_performance', 'Below Allowable Cost')->count(),
                'with_allowable' => $filteredProjects->where('status', 'completed')->where('cost_performance', 'Within Allowable Cost')->count(),
                'above' => $filteredProjects->where('status', 'completed')->where('cost_performance', 'Above Allowable Cost')->count(),
                'total' => $filteredProjects->where('status', 'completed')->count(),
            ],
        ];

        $completedProjects = $filteredProjects->where('status', 'completed');
        $inProgressProjects = $filteredProjects->where('status', 'in progress');
        $suspendedProjects = $filteredProjects->where('status', 'suspended');

        $executiveKpis = [
            'total_projects' => [
                'total' => $totalProjects,
                'completed' => $completedProjects->count(),
                'in_progress' => $inProgressProjects->count(),
                'suspended' => $suspendedProjects->count(),
            ],
            'completed_amount_at_completion' => [
                'total' => $completedProjects->sum('cost_at_completion'),
                'count' => $completedProjects->count(),
            ],
            'completed_revised_budget' => [
                'total' => $completedProjects->sum('revised_budget'),
                'count' => $completedProjects->count(),
            ],
            'in_progress_revised_budget' => [
                'total' => $inProgressProjects->sum('revised_budget'),
                'count' => $inProgressProjects->count(),
            ],
            'suspended_revised_budget' => [
                'total' => $suspendedProjects->sum('revised_budget'),
                'count' => $suspendedProjects->count(),
            ],
        ];

        $buildGrouped = function ($items, $groupKey, $statusKey, $statuses) use ($totalProjects) {
            $groups = [];
            foreach ($items as $item) {
                $group = $item[$groupKey];
                $status = $item[$statusKey];
                if (!isset($groups[$group])) {
                    $groups[$group] = [];
                }
                if (!isset($groups[$group][$status])) {
                    $groups[$group][$status] = 0;
                }
                $groups[$group][$status]++;
            }
            $labels = [];
            $datasets = [];
            foreach ($statuses as $status) {
                $datasets[] = [
                    'name' => $status,
                    'values' => [],
                ];
            }
            foreach ($groups as $group => $counts) {
                $labels[] = $group;
                foreach ($statuses as $i => $status) {
                    $datasets[$i]['values'][] = $counts[$status] ?? 0;
                }
            }
            return [
                'labels' => $labels,
                'datasets' => $datasets,
                'total' => $items->count(),
            ];
        };

        $scheduleStatuses = ['Behind Schedule', 'Ahead of Schedule', 'On Schedule'];
        $budgetStatuses = ['Under Budget', 'On Budget', 'Above Budget'];
        $costStatuses = ['Below Allowable Cost', 'Within Allowable Cost', 'Above Allowable Cost'];

        $variationGroups = [
            'Projects with Addition' => $completedProjects->filter(fn($p) => $p['variation'] > 0),
            'Projects with Omission' => $completedProjects->filter(fn($p) => $p['variation'] < 0),
            'Projects with No Variation' => $completedProjects->filter(fn($p) => $p['variation'] == 0),
        ];

        $variationLabels = array_keys($variationGroups);
        $variationDatasets = [
            ['name' => 'Behind Schedule', 'values' => [], 'counts' => []],
            ['name' => 'Ahead of Schedule', 'values' => [], 'counts' => []],
            ['name' => 'On Schedule', 'values' => [], 'counts' => []],
        ];
        $variationGroupTotals = [];
        foreach ($variationGroups as $label => $groupProjects) {
            $groupTotal = $groupProjects->count();
            $variationGroupTotals[] = $groupTotal;
            if ($groupTotal === 0) {
                foreach ($variationDatasets as &$ds) {
                    $ds['values'][] = 0;
                    $ds['counts'][] = 0;
                }
                continue;
            }
            foreach ($scheduleStatuses as $i => $status) {
                $count = $groupProjects->where('schedule_status', $status)->count();
                $variationDatasets[$i]['counts'][] = $count;
                $variationDatasets[$i]['values'][] = $groupTotal > 0 ? round(($count / $groupTotal) * 100) : 0;
            }
        }

        $deliveryMethods = ['DB', 'DBB'];
        $deliveryLabels = [];
        $deliveryDatasets = [
            ['name' => 'Behind Schedule', 'values' => [], 'counts' => []],
            ['name' => 'Ahead of Schedule', 'values' => [], 'counts' => []],
            ['name' => 'On Schedule', 'values' => [], 'counts' => []],
        ];
        $deliveryGroupTotals = [];
        foreach ($deliveryMethods as $method) {
            $deliveryLabels[] = $method;
            $methodProjects = $completedProjects->where('delivery_method', $method);
            $groupTotal = $methodProjects->count();
            $deliveryGroupTotals[] = $groupTotal;
            foreach ($scheduleStatuses as $i => $status) {
                $count = $methodProjects->where('schedule_status', $status)->count();
                $deliveryDatasets[$i]['counts'][] = $count;
                $deliveryDatasets[$i]['values'][] = $groupTotal > 0 ? round(($count / $groupTotal) * 100) : 0;
            }
        }

        $sectors = ['Private', 'Client / Engineering Team', 'Government'];
        $sectorLabels = [];
        $sectorCostDatasets = [
            ['name' => 'Below Allowable Cost', 'values' => [], 'counts' => []],
            ['name' => 'Within Allowable Cost', 'values' => [], 'counts' => []],
            ['name' => 'Above Allowable Cost', 'values' => [], 'counts' => []],
        ];
        $sectorBudgetDatasets = [
            ['name' => 'Under Budget', 'values' => [], 'counts' => []],
            ['name' => 'On Budget', 'values' => [], 'counts' => []],
            ['name' => 'Above Budget', 'values' => [], 'counts' => []],
        ];
        $sectorGroupTotals = [];
        foreach ($sectors as $sector) {
            $sectorLabels[] = $sector === 'Client / Engineering Team' ? 'Client Engineering Team' : ($sector === 'Private' ? 'Private Consultancy' : 'Government Consultancy');
            $sectorProjects = $completedProjects->where('consultancy_sector', $sector);
            $groupTotal = $sectorProjects->count();
            $sectorGroupTotals[] = $groupTotal;
            foreach ($costStatuses as $i => $status) {
                $count = $sectorProjects->where('cost_performance', $status)->count();
                $sectorCostDatasets[$i]['counts'][] = $count;
                $sectorCostDatasets[$i]['values'][] = $groupTotal > 0 ? round(($count / $groupTotal) * 100) : 0;
            }
            foreach ($budgetStatuses as $i => $status) {
                $count = $sectorProjects->where('budget_performance', $status)->count();
                $sectorBudgetDatasets[$i]['counts'][] = $count;
                $sectorBudgetDatasets[$i]['values'][] = $groupTotal > 0 ? round(($count / $groupTotal) * 100) : 0;
            }
        }

        $buildSummary = function ($groupTotals, $datasets) {
            $summaries = [];
            foreach ($groupTotals as $idx => $total) {
                $values = array_map(fn($ds) => $ds['values'][$idx] ?? 0, $datasets);
                $nonEmpty = array_filter($values, fn($v) => $v > 0);
                $summaries[] = [
                    'total' => $total,
                    'highest' => $values ? max($values) : 0,
                    'lowest' => $values ? min($values) : 0,
                    'average' => $total > 0 && count($values) > 0 ? round(array_sum($values) / count($values)) : 0,
                    'empty' => $total === 0,
                ];
            }
            return $summaries;
        };

        $comparativeAnalytics = [
            'time_by_variation' => [
                'title' => 'Time Performance by Contract Variation',
                'description' => 'Compare completed projects by schedule status across contract variation categories.',
                'xaxis' => $variationLabels,
                'series' => $variationDatasets,
                'summaries' => $buildSummary($variationGroupTotals, $variationDatasets),
                'icons' => ['➕', '➖', '—'],
            ],
            'time_by_delivery' => [
                'title' => 'Time Performance by Delivery Method',
                'description' => 'Compare completed projects by schedule status across delivery methods.',
                'xaxis' => $deliveryLabels,
                'series' => $deliveryDatasets,
                'summaries' => $buildSummary($deliveryGroupTotals, $deliveryDatasets),
                'icons' => ['🏗', '🏢', '⚙'],
            ],
            'cost_by_sector' => [
                'title' => 'Cost Performance by Consultancy Sector',
                'description' => 'Compare completed projects by cost performance across consultancy sectors.',
                'xaxis' => $sectorLabels,
                'series' => $sectorCostDatasets,
                'summaries' => $buildSummary($sectorGroupTotals, $sectorCostDatasets),
                'icons' => ['🏢', '👷', '🏛'],
            ],
            'budget_by_sector' => [
                'title' => 'Budget Performance by Consultancy Sector',
                'description' => 'Compare completed projects by budget performance across consultancy sectors.',
                'xaxis' => $sectorLabels,
                'series' => $sectorBudgetDatasets,
                'summaries' => $buildSummary($sectorGroupTotals, $sectorBudgetDatasets),
                'icons' => ['🏢', '👷', '🏛'],
            ],
        ];

        $perPage = (int) ($request->filled('per_page') ? $request->per_page : 15);
        $page = (int) ($request->filled('page') ? $request->page : 1);
        $totalProjects = $filteredProjects->count();
        $totalPages = max(1, (int) ceil($totalProjects / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;
        $paginatedProjects = $filteredProjects->slice($offset, $perPage)->values();

        return response()->json([
            'projects' => $paginatedProjects->values()->all(),
            'all_projects' => $filteredProjects->values()->all(),
            'kpis' => $kpis,
            'executive_kpis' => $executiveKpis,
            'filters' => $filters,
            'total' => $totalProjects,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
            'analytics' => $analytics,
            'comparative_analytics' => $comparativeAnalytics,
        ]);
    }
}

