<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectSite;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectSiteController extends Controller
{
    public function index(Request $request): View
    {
        $query = ProjectSite::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $sites = $query->latest()->paginate(15);

        return view('admin.materials.project-sites.index', compact('sites'));
    }

    public function create(): View
    {
        return view('admin.materials.project-sites.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
        ]);

        $validated['created_by'] = auth()->id();

        ProjectSite::create($validated);

        return redirect()->route('admin.project-sites.index')->with('success', 'Project site created successfully.');
    }

    public function update(Request $request, ProjectSite $projectSite)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
        ]);

        $projectSite->update($validated);

        return redirect()->route('admin.project-sites.index')->with('success', 'Project site updated successfully.');
    }

    public function destroy(ProjectSite $projectSite)
    {
        $projectSite->delete();

        return redirect()->route('admin.project-sites.index')->with('success', 'Project site deleted successfully.');
    }
}
