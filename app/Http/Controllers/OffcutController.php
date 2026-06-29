<?php

namespace App\Http\Controllers;

use App\Models\Offcut;
use App\Http\Requests\StoreOffcutRequest;
use App\Http\Requests\UpdateOffcutRequest;

class OffcutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Offcut::query();

        if (request('status')) {
            $query->where('status', request('status'));
        }
        if (request('diameter')) {
            $query->where('bar_diameter', request('diameter'));
        }

        $offcuts = $query->latest()->paginate(10)->withQueryString();

        // Stats for cards
        $availableCount = (clone $query)->where('status', 'Available')->count();
        $usedCount = (clone $query)->where('status', 'Used')->count();

        return view('admin.rebar.offcuts.index', compact('offcuts', 'availableCount', 'usedCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Manual creation if needed
        return view('admin.rebar.offcuts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOffcutRequest $request)
    {
        Offcut::create($request->validated());
        return redirect()->route('admin.rebar.offcuts.index')->with('success', 'Off-cut registered manually.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Offcut $offcut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offcut $offcut)
    {
        return view('admin.rebar.offcuts.edit', compact('offcut'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOffcutRequest $request, Offcut $offcut)
    {
        $offcut->update($request->validated());
        return redirect()->route('admin.rebar.offcuts.index')->with('success', 'Off-cut updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offcut $offcut)
    {
        $offcut->delete();
        return redirect()->route('admin.rebar.offcuts.index')->with('success', 'Off-cut deleted.');
    }
}

