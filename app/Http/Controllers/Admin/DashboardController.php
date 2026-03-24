<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $projects = \App\Models\Project::all();

        $stats = [
            'total_active' => \App\Models\Project::where('closing_status', 'Not Completed')->count(),
            'total_contract_value' => $projects->sum(fn($p) => $p->total_project_value),
            'total_allowable_cost' => $projects->sum('total_allowable_cost'),
            'cost_at_completion' => $projects->sum('cost_at_completion'),
            'near_deadline' => \App\Models\Project::where('baseline_finish_date', '<=', now()->addDays(30))
                ->where('closing_status', 'Not Completed')
                ->count(),
            'completed' => \App\Models\Project::whereIn('closing_status', [
                'FA Received',
                'PA Received',
                'PPA Received'
            ])->count(),
        ];

        // Chart Data (Sample structure)
        $projectTypes = \App\Models\Project::groupBy('project_type')
            ->selectRaw('project_type, count(*) as count')
            ->pluck('count', 'project_type');

        return view('admin.dashboard', compact('stats', 'projectTypes', 'projects'));
    }
}
