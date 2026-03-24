<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\ProjectWeeklyUpdate;

class ProjectWeeklyUpdateController extends Controller
{
    public function index()
    {
        $updates = ProjectWeeklyUpdate::with('project')->latest()->get();
        return view('admin.weekly-updates.index', compact('updates'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.weekly-updates.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'contact_person' => 'nullable|string',
            'responsible_person' => 'nullable|string',
            'expected_completion_date' => 'nullable|date',
            'remaining_items' => 'nullable|string',
            'status' => 'nullable|string',
            'activity_planned_next_week' => 'nullable|string',
            'constraints' => 'nullable|string',
        ]);

        ProjectWeeklyUpdate::create($validated);

        return redirect()->route('admin.weekly-updates.index')->with('success', 'Weekly update created successfully.');
    }

    public function show(ProjectWeeklyUpdate $weekly_update)
    {
        return view('admin.weekly-updates.show', ['update' => $weekly_update]);
    }

    public function edit(ProjectWeeklyUpdate $weekly_update)
    {
        $projects = Project::all();
        return view('admin.weekly-updates.edit', [
            'update' => $weekly_update,
            'projects' => $projects
        ]);
    }

    public function update(Request $request, ProjectWeeklyUpdate $weekly_update)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'contact_person' => 'nullable|string',
            'responsible_person' => 'nullable|string',
            'expected_completion_date' => 'nullable|date',
            'remaining_items' => 'nullable|string',
            'status' => 'nullable|string',
            'activity_planned_next_week' => 'nullable|string',
            'constraints' => 'nullable|string',
        ]);

        $weekly_update->update($validated);

        return redirect()->route('admin.weekly-updates.index')->with('success', 'Weekly update updated successfully.');
    }

    public function destroy(ProjectWeeklyUpdate $weekly_update)
    {
        $weekly_update->delete();
        return redirect()->route('admin.weekly-updates.index')->with('success', 'Weekly update deleted successfully.');
    }
}
