<?php

namespace App\Http\Controllers;

use App\Models\RebarCuttingLog;
use App\Http\Requests\StoreRebarCuttingLogRequest;
use App\Http\Requests\UpdateRebarCuttingLogRequest;

class RebarCuttingLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = RebarCuttingLog::with('requirement', 'offcut');

        if (request('requirement_id')) {
            $query->where('rebar_requirement_id', request('requirement_id'));
        }

        $logs = $query->latest()->paginate(10)->withQueryString();

        return view('admin.rebar.cutting_logs.index', compact('logs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Typically created from the requirement view, but can exist standalone
        $requirements = \App\Models\RebarRequirement::latest()->limit(50)->get();
        return view('admin.rebar.cutting_logs.create', compact('requirements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRebarCuttingLogRequest $request)
    {
        $validated = $request->validated();

        $log = RebarCuttingLog::create($validated);

        // Smart Automation: Create Off-cut if remaining length > 300mm
        if ($log->remaining_length > 300) {
            $offcut = \App\Models\Offcut::create([
                'bar_diameter' => $log->bar_diameter,
                'length' => $log->remaining_length,
                'quantity' => 1,
                'storage_location' => 'Generated from Cutting Log #' . $log->id,
                'status' => 'Available',
                'remarks' => 'Auto-generated off-cut from requirement ' . $log->requirement->tracking_id,
            ]);

            // Link back
            $log->offcut_id = $offcut->id;
            $log->save();
        }

        return redirect()->back()->with('success', 'Cutting log recorded successfully.' . ($log->offcut_id ? ' Off-cut auto-generated.' : ''));
    }

    /**
     * Display the specified resource.
     */
    public function show(RebarCuttingLog $rebarCuttingLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RebarCuttingLog $rebarCuttingLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRebarCuttingLogRequest $request, RebarCuttingLog $rebarCuttingLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RebarCuttingLog $cuttingLog)
    {
        // If there's an associated offcut that is still 'Available', we should perhaps delete it or warn?
        // For now, let's correct parameter name and just delete.
        $cuttingLog->delete();
        return redirect()->back()->with('success', 'Cutting log deleted.');
    }
}

