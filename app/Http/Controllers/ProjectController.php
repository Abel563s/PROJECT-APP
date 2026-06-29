<?php

namespace App\Http\Controllers;

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

        $projects = $query->latest()->get();
        return view('admin.projects.index', compact('projects'));
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
            'project_type' => 'nullable|in:Building,Fit-Out,Infrastructure,Mixed (Bui/Road),Mixed (Bui/Fit-Out)',
            'delivery_method' => 'nullable|in:DB,DBB,DB-LS,DB-ADM,DBB-ADM,DB-CP',
            'project_client' => 'nullable|string',
            'consultant' => 'nullable|string',
            'consultancy_sector' => 'nullable|in:Government,Private,Client / Engineering Team',
            'scope' => 'nullable|string',
            'contract_budget' => 'nullable|numeric',
            'variation' => 'nullable|numeric',
            'supplementary' => 'nullable|numeric',
            'total_allowable_cost' => 'nullable|numeric',
            'cost_at_completion' => 'nullable|numeric',
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

        Project::create($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function analytics()
    {
        $projects = Project::all();

        $totalPortfolioValue = $projects->sum(fn($p) => (float) $p->total_project_value);
        $totalAllowableCost = $projects->sum(fn($p) => (float) $p->total_allowable_cost);
        $totalCostAtCompletion = $projects->sum(fn($p) => (float) $p->cost_at_completion);
        $totalVariations = $projects->sum(fn($p) => (float) $p->variation);

        $costVariance = $totalPortfolioValue - $totalCostAtCompletion;
        $avgScheduleVariance = $projects->count() > 0 ? $projects->avg(fn($p) => $p->schedule_variance) : 0;

        // Distribution Data
        $projectTypes = Project::groupBy('project_type')
            ->selectRaw('project_type, count(*) as count')
            ->pluck('count', 'project_type');

        $clientValues = Project::groupBy('project_client')
            ->whereNotNull('project_client')
            ->selectRaw('project_client, sum(contract_budget + variation + supplementary) as total_value')
            ->pluck('total_value', 'project_client');

        $sectorValues = Project::groupBy('consultancy_sector')
            ->whereNotNull('consultancy_sector')
            ->selectRaw('consultancy_sector, sum(contract_budget + variation + supplementary) as total_value')
            ->pluck('total_value', 'consultancy_sector');

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
            'projects'
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
            'project_type' => 'nullable|in:Building,Fit-Out,Infrastructure,Mixed (Bui/Road),Mixed (Bui/Fit-Out)',
            'delivery_method' => 'nullable|in:DB,DBB,DB-LS,DB-ADM,DBB-ADM,DB-CP',
            'project_client' => 'nullable|string',
            'consultant' => 'nullable|string',
            'consultancy_sector' => 'nullable|in:Government,Private,Client / Engineering Team',
            'scope' => 'nullable|string',
            'contract_budget' => 'nullable|numeric',
            'variation' => 'nullable|numeric',
            'supplementary' => 'nullable|numeric',
            'total_allowable_cost' => 'nullable|numeric',
            'cost_at_completion' => 'nullable|numeric',
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

    public function closeoutIndex()
    {
        $projects = Project::latest()->get();
        return view('admin.projects.closeout_index', compact('projects'));
    }

    public function closeoutShow(Project $project)
    {
        return view('admin.projects.closeout', compact('project'));
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }
}
