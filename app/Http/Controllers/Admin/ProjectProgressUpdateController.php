<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectProgressUpdate;

class ProjectProgressUpdateController extends Controller
{
    public function index()
    {
        $updates = ProjectProgressUpdate::with('project')->latest()->get();
        return view('admin.progress-updates.index', compact('updates'));
    }

    public function create()
    {
        $projects = Project::all();
        if (request()->has('partial')) {
            return view('admin.progress-updates.create-modal', compact('projects'));
        }
        return view('admin.progress-updates.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'progress_planned' => 'required|numeric|min:0',
            'progress_actual' => 'required|numeric|min:0',
            'revenue_planned' => 'nullable|numeric|min:0',
            'revenue_actual' => 'nullable|numeric|min:0',
            'completion_date' => 'nullable|date',
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
        $projects = Project::all();
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
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'progress_planned' => 'required|numeric|min:0',
            'progress_actual' => 'required|numeric|min:0',
            'revenue_planned' => 'nullable|numeric|min:0',
            'revenue_actual' => 'nullable|numeric|min:0',
            'completion_date' => 'nullable|date',
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

        $progress_update->update($validated);

        return redirect()->route('admin.progress-updates.index')->with('success', 'Progress update updated successfully.');
    }

    public function destroy(ProjectProgressUpdate $progress_update)
    {
        $progress_update->delete();
        return redirect()->route('admin.progress-updates.index')->with('success', 'Progress update deleted successfully.');
    }
}
