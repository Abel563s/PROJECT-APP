<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectPaymentController extends Controller
{
    /**
     * Display a listing of projects for payment management.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhere('custom_id', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->get();
        return view('admin.projects.payments.index', compact('projects'));
    }

    /**
     * Manage payments for a specific project.
     */
    public function manage(Project $project)
    {
        $project->load('payments');

        $totalSubmitted = $project->payments->sum('submitted_amount');
        $totalCertified = $project->payments->sum('certified_amount');
        $totalPaid = $project->payments->sum('amount_paid');

        $contractValue = $project->total_project_value;
        $outstandingBalance = $contractValue - $totalCertified;
        $paymentProgress = $contractValue > 0 ? ($totalPaid / $contractValue) * 100 : 0;

        return view('admin.projects.payments.manage', compact(
            'project',
            'totalSubmitted',
            'totalCertified',
            'totalPaid',
            'contractValue',
            'outstandingBalance',
            'paymentProgress'
        ));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'certificate_number' => 'required|string',
            'certificate_date' => 'required|date',
            'submitted_amount' => 'nullable|numeric|min:0',
            'submitted_date' => 'nullable|date',
            'certified_amount' => 'nullable|numeric|min:0',
            'certified_date' => 'nullable|date',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        // Prevent payment exceeding contract value (optional logic, could be adjusted)
        $currentTotalPaid = $project->payments->sum('amount_paid');
        $newTotalPaid = $currentTotalPaid + (float) ($validated['amount_paid'] ?? 0);

        if ($newTotalPaid > $project->total_project_value && $project->total_project_value > 0) {
            return back()->with('error', 'Total payment cannot exceed contract value. Remaining balance: ' . number_format($project->total_project_value - $currentTotalPaid, 2));
        }

        DB::beginTransaction();
        try {
            $payment = new ProjectPayment($validated);
            $payment->project_id = $project->id;
            $payment->save();

            // Update project totals
            $project->total_certified = $project->payments()->sum('certified_amount');
            $project->total_paid = $project->payments()->sum('amount_paid');
            $project->save();

            DB::commit();
            return back()->with('success', 'Payment certificate added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save payment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified payment detail (for modal).
     */
    public function show(ProjectPayment $payment)
    {
        return response()->json($payment);
    }
}
