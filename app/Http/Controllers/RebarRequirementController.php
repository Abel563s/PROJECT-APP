<?php

namespace App\Http\Controllers;

use App\Models\RebarRequirement;
use App\Http\Requests\StoreRebarRequirementRequest;
use App\Http\Requests\UpdateRebarRequirementRequest;

class RebarRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = RebarRequirement::query();

        // Filters
        if (request('diameter')) {
            $query->where('bar_diameter', request('diameter'));
        }
        if (request('element')) {
            $query->where('structural_element', 'like', '%' . request('element') . '%');
        }
        if (request('date')) {
            $query->whereDate('created_at', request('date'));
        }

        // Search
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('tracking_id', 'like', "%{$search}%")
                    ->orWhere('structural_element', 'like', "%{$search}%")
                    ->orWhere('drawing_reference', 'like', "%{$search}%");
            });
        }

        // Totals for widget
        $totalRequirements = (clone $query)->count();
        $totalLength = (clone $query)->sum('total_length'); // stored in meters

        $requirements = $query->latest()->paginate(10)->withQueryString();

        return view('admin.rebar.requirements.index', compact('requirements', 'totalRequirements', 'totalLength'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rebar.requirements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRebarRequirementRequest $request)
    {
        RebarRequirement::create($request->validated());

        return redirect()->route('admin.rebar.requirements.index')
            ->with('success', 'Rebar requirement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RebarRequirement $requirement)
    {
        // Parameter name mismatch in resource controller generation, fixing to match model binding
        return view('admin.rebar.requirements.show', compact('requirement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RebarRequirement $requirement)
    {
        return view('admin.rebar.requirements.edit', compact('requirement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRebarRequirementRequest $request, RebarRequirement $requirement)
    {
        $requirement->update($request->validated());

        // Recalculate total length in case dimensions changed
        $requirement->total_length = ($requirement->required_length * $requirement->quantity) / 1000;
        $requirement->save();

        return redirect()->route('admin.rebar.requirements.index')
            ->with('success', 'Rebar requirement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RebarRequirement $requirement)
    {
        $requirement->delete();

        return redirect()->route('admin.rebar.requirements.index')
            ->with('success', 'Rebar requirement deleted successfully.');
    }
}

