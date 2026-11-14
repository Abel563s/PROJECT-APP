<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectProgressUpdate;
use App\Support\ProjectOrganizationFilters;
use Illuminate\Validation\Rule;

class ProjectProgressUpdateController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectProgressUpdate::with('project.coordinators');
        ProjectOrganizationFilters::apply($query, $request);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('project', function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhere('custom_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('project_sector') && $request->project_sector !== 'All Sectors') {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('project_type', $request->project_sector);
            });
        }

        if ($request->filled('data_date')) {
            $query->whereDate('data_date', $request->data_date);
        }

        $revenueTotals = (clone $query)->getQuery()
            ->reorder()
            ->selectRaw('
                COALESCE(SUM(revenue_planned), 0) as total_revenue_planned,
                COALESCE(SUM(revenue_actual), 0) as total_revenue_actual,
                COALESCE(SUM(revenue_planned_weekly), 0) as total_revenue_planned_weekly,
                COALESCE(SUM(revenue_actual_weekly), 0) as total_revenue_actual_weekly
            ')
            ->first();

        $totalRevenuePlanned = (float) $revenueTotals->total_revenue_planned;
        $totalRevenueActual = (float) $revenueTotals->total_revenue_actual;
        $totalRevenuePlannedWeekly = (float) $revenueTotals->total_revenue_planned_weekly;
        $totalRevenueActualWeekly = (float) $revenueTotals->total_revenue_actual_weekly;

        $allUpdates = (clone $query)->getQuery()->reorder()->get();
        $spiSum = 0.0;
        $spiCount = 0;
        foreach ($allUpdates as $u) {
            if ((float) $u->progress_planned > 0) {
                $spiSum += (float) $u->progress_actual / (float) $u->progress_planned;
                $spiCount++;
            }
        }
        $avgSpiWeekly = $spiCount > 0 ? ($spiSum / $spiCount) * 100 : 0;

        $latestDataDate = (clone $query)->latest('data_date')->value('data_date');
        $updates = $query->latest()->paginate(15)->withQueryString();
        $projects = Project::orderBy('project_name')->get(['id', 'project_code', 'project_name']);
        return view('admin.progress-updates.index', array_merge(
            compact('updates', 'projects', 'totalRevenuePlanned', 'totalRevenueActual', 'totalRevenuePlannedWeekly', 'totalRevenueActualWeekly', 'avgSpiWeekly', 'latestDataDate'),
            ProjectOrganizationFilters::options($request)
        ));
    }

    public function create()
    {
        $user = request()->user();
        abort_unless($user->isAdmin() || $user->isSuperadmin() || $user->isUser(), 403);
        $projects = $this->availableProjects($user);

        if (request()->has('partial')) {
            return view('admin.progress-updates.create-modal', compact('projects'));
        }
        return view('admin.progress-updates.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', Rule::exists('projects', 'id')],
            'progress_planned' => 'required|numeric|min:0',
            'progress_actual' => 'required|numeric|min:0',
            'revenue_planned' => 'nullable|numeric|min:0',
            'revenue_actual' => 'nullable|numeric|min:0',
            'revenue_planned_weekly' => 'nullable|numeric|min:0',
            'revenue_actual_weekly' => 'nullable|numeric|min:0',
            'cost_planned' => 'nullable|numeric|min:0',
            'cost_actual' => 'nullable|numeric|min:0',
            'cost_planned_weekly' => 'nullable|numeric|min:0',
            'cost_actual_weekly' => 'nullable|numeric|min:0',
            'completion_date' => 'nullable|date',
            'data_date' => 'required|date',
            'top_constraints' => 'nullable|string',
            'client_issue' => 'nullable|string',
            'design_completion_approval' => 'nullable|string',
            'material_submittal_approval' => 'nullable|string',
            'material_delivery' => 'nullable|string',
            'labor' => 'nullable|string',
            'machinery_equipment' => 'nullable|string',
            'subcontractor' => 'nullable|string',
            'finance' => 'nullable|string',
            'operation_constraint' => 'nullable|string',
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $this->authorize('create', [ProjectProgressUpdate::class, $project]);

        ProjectProgressUpdate::create($validated);

        return redirect()->route('admin.progress-updates.index')->with('success', 'Progress update created successfully.');
    }

    public function show(ProjectProgressUpdate $progress_update)
    {
        if (request()->has('partial')) {
            return view('admin.progress-updates.show-modal', ['update' => $progress_update]);
        }
        return view('admin.progress-updates.show', ['update' => $progress_update]);
    }

    public function edit(ProjectProgressUpdate $progress_update)
    {
        $this->authorize('update', $progress_update);
        $projects = $this->availableProjects(request()->user());

        if (request()->has('partial')) {
            return view('admin.progress-updates.edit-modal', [
                'update' => $progress_update,
                'projects' => $projects
            ]);
        }
        return view('admin.progress-updates.edit', [
            'update' => $progress_update,
            'projects' => $projects
        ]);
    }

    public function update(Request $request, ProjectProgressUpdate $progress_update)
    {
        $this->authorize('update', $progress_update);

        $validated = $request->validate([
            'project_id' => ['required', Rule::exists('projects', 'id')],
            'progress_planned' => 'required|numeric|min:0',
            'progress_actual' => 'required|numeric|min:0',
            'revenue_planned' => 'nullable|numeric|min:0',
            'revenue_actual' => 'nullable|numeric|min:0',
            'revenue_planned_weekly' => 'nullable|numeric|min:0',
            'revenue_actual_weekly' => 'nullable|numeric|min:0',
            'cost_planned' => 'nullable|numeric|min:0',
            'cost_actual' => 'nullable|numeric|min:0',
            'cost_planned_weekly' => 'nullable|numeric|min:0',
            'cost_actual_weekly' => 'nullable|numeric|min:0',
            'completion_date' => 'nullable|date',
            'data_date' => 'nullable|date',
            'top_constraints' => 'nullable|string',
            'client_issue' => 'nullable|string',
            'design_completion_approval' => 'nullable|string',
            'material_submittal_approval' => 'nullable|string',
            'material_delivery' => 'nullable|string',
            'labor' => 'nullable|string',
            'machinery_equipment' => 'nullable|string',
            'subcontractor' => 'nullable|string',
            'finance' => 'nullable|string',
            'operation_constraint' => 'nullable|string',
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $this->authorize('update', $project);

        if (!$request->user()->isAdmin() && !$request->user()->isSuperadmin()) {
            unset($validated['data_date']);
        }

        $progress_update->update($validated);

        return redirect()->route('admin.progress-updates.index')->with('success', 'Progress update updated successfully.');
    }

    public function destroy(ProjectProgressUpdate $progress_update)
    {
        $this->authorize('delete', $progress_update);

        $progress_update->delete();
        return redirect()->route('admin.progress-updates.index')->with('success', 'Progress update deleted successfully.');
    }

    private function availableProjects(\App\Models\User $user)
    {
        if ($user->isSuperadmin()) {
            return Project::orderBy('project_name')->get();
        }

        if ($user->isAdmin()) {
            return Project::where('business_unit_id', $user->business_unit_id)->orderBy('project_name')->get();
        }

        return $user->assignedProjects()->orderBy('project_name')->get();
    }
}
