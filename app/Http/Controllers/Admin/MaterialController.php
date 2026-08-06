<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManageMaterial;
use App\Models\ProjectSite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->scopeProjectManagementForUser(ManageMaterial::query());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('material_code', 'like', "%{$search}%")
                    ->orWhere('material_name', 'like', "%{$search}%")
                    ->orWhere('supplier_name', 'like', "%{$search}%")
                    ->orWhere('procurement_type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('project_client')) {
            $query->where('supplier_name', $request->project_client);
        }

        if ($request->filled('project_type')) {
            $query->where('discipline', $request->project_type);
        }

        if ($request->filled('consultancy_sector')) {
            $query->where('procurement_type', $request->consultancy_sector);
        }

        if ($request->filled('closing_status')) {
            $query->where('approval', $request->closing_status);
        }

        $records = $query->latest()->paginate(10);
        $departments = $this->departmentsForUser();
        $projects = \App\Models\Project::orderBy('project_name')->get(['id', 'project_code', 'project_name']);

        return view('admin.materials.index', compact('records', 'departments', 'projects'));
    }

    public function create(): View
    {
        $this->authorizeCreateHrRecord();

        if (request()->has('partial')) {
            return view('admin.materials.create-modal', [
                'departments' => $this->departmentsForUser(),
                'projects' => \App\Models\Project::where('status', 'active')->orderBy('project_name')->get(['id', 'project_code', 'project_name'])
            ]);
        }

        return view('admin.materials.create', [
            'departments' => $this->departmentsForUser(),
            'projects' => \App\Models\Project::where('status', 'active')->orderBy('project_name')->get(['id', 'project_code', 'project_name'])
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeCreateHrRecord();

        try {
            if ($request->expectsJson()) {
                $modalValidated = $request->validate([
                    'material_name' => 'nullable|string|max:255',
                    'project_id' => 'nullable|exists:projects,id',
                    'supplier_name' => 'nullable|string|max:255',
                    'discipline' => 'nullable|string',
                    'procurement_product' => 'nullable|string',
                    'supply_route' => 'nullable|string',
                    'approval' => 'nullable|string',
                    'procurement_type' => 'nullable|string',
                    'design_planned' => 'nullable|date',
                    'design_actual' => 'nullable|date',
                    'spec_preparation' => 'nullable|date',
                    'request_order' => 'nullable|date',
                    'supplier_identification' => 'nullable|date',
                    'material_submittal' => 'nullable|date',
                    'material_approval' => 'nullable|date',
                    'finance' => 'nullable|date',
                    'lc' => 'nullable|date',
                    'payment' => 'nullable|date',
                    'production_comp' => 'nullable|date',
                    'shipment_of_material' => 'nullable|date',
                    'logistics_to_dry_port' => 'nullable|date',
                    'clearance_of_material' => 'nullable|date',
                    'delivery_to_site' => 'nullable|date',
                    'planned_start_date' => 'nullable|date',
                    'production_days' => 'nullable|integer',
                    'shipment_days' => 'nullable|integer',
                ]);

                $data = [
                    'material_code' => 'MT-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4)),
                    'material_name' => $modalValidated['material_name'] ?? $request->input('material_name'),
                    'project_id' => $modalValidated['project_id'] ?? $request->input('project_id'),
                    'discipline' => $modalValidated['discipline'] ?? $request->input('discipline'),
                    'procurement_product' => $modalValidated['procurement_product'] ?? $request->input('procurement_product'),
                    'supplier_name' => $modalValidated['supplier_name'] ?? $request->input('supplier_name'),
                    'procurement_type' => $modalValidated['procurement_type'] ?? $request->input('procurement_type'),
                    'supply_route' => $modalValidated['supply_route'] ?? $request->input('supply_route'),
                    'approval' => $modalValidated['approval'] ?? $request->input('approval'),
                    'design_planned' => $request->input('design_planned'),
                    'design_actual' => $request->input('design_actual'),
                    'spec_preparation' => $request->input('spec_preparation'),
                    'request_order' => $request->input('request_order'),
                    'supplier_identification' => $request->input('supplier_identification'),
                    'material_submittal' => $request->input('material_submittal'),
                    'material_approval' => $request->input('material_approval'),
                    'finance' => $request->input('finance'),
                    'lc' => $request->input('lc'),
                    'payment' => $request->input('payment'),
                    'production_comp' => $request->input('production_comp'),
                    'shipment_of_material' => $request->input('shipment_of_material'),
                    'logistics_to_dry_port' => $request->input('logistics_to_dry_port'),
                    'clearance_of_material' => $request->input('clearance_of_material'),
                    'delivery_to_site' => $request->input('delivery_to_site'),
                    'planned_start_date' => $request->input('planned_start_date'),
                    'production_days' => $request->input('production_days'),
                    'shipment_days' => $request->input('shipment_days'),
                    'payment' => $request->input('payment'),
                ];
                if (Schema::hasTable('manage_materials')) {
                    $data['created_by'] = auth()->id();
                }

                $material = DB::transaction(function () use ($data) {
                    return ManageMaterial::query()->create($data);
                });
            } else {
                $validated = $request->validate([
                    'material_code' => 'nullable|string|max:255',
                    'material_name' => 'required|string|max:255',
                    'project_id' => 'nullable|exists:projects,id',
                    'discipline' => 'required|string',
                    'procurement_product' => 'required|string',
                    'supplier_name' => 'required|string|max:255',
                    'procurement_type' => 'required|string',
                    'supply_route' => 'nullable|string',
                    'design_planned' => 'nullable|date',
                    'design_actual' => 'nullable|date',
                    'spec_preparation' => 'nullable|date',
                    'request_order' => 'nullable|date',
                    'supplier_identification' => 'nullable|date',
                    'material_submittal' => 'nullable|date',
                    'material_approval' => 'nullable|date',
                    'finance' => 'nullable|date',
                    'lc' => 'nullable|date',
                    'payment' => 'nullable|date',
                    'production_comp' => 'nullable|date',
                    'shipment_of_material' => 'nullable|date',
                    'logistics_to_dry_port' => 'nullable|date',
                    'clearance_of_material' => 'nullable|date',
                    'delivery_to_site' => 'nullable|date',
                    'planned_start_date' => 'nullable|date',
                    'production_days' => 'nullable|integer',
                    'shipment_days' => 'nullable|integer',
                ]);

                $validated['material_code'] = 'MT-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
                if (Schema::hasTable('manage_materials')) {
                    $validated['created_by'] = auth()->id();
                }

                $material = DB::transaction(function () use ($validated) {
                    return ManageMaterial::query()->create($validated);
                });
            }
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 500);
            }

            throw $e;
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'id' => $material->id], 201);
        }

        return redirect()->route('admin.material.index')->with('success', 'Material record submitted successfully.');
    }

    public function show(ManageMaterial $material): View
    {
        $this->authorizeViewHrRecord($material);

        $material->load('project');

        if (request()->has('partial')) {
            return view('admin.materials.show-modal', ['material' => $material]);
        }

        return view('admin.materials.show', ['material' => $material]);
    }

    public function edit(ManageMaterial $material): View
    {
        $this->authorizeEditHrRecord($material);

        $projects = \App\Models\Project::where('status', 'active')->orderBy('project_name')->get(['id', 'project_code', 'project_name']);

        if (request()->has('partial')) {
            return view('admin.materials.edit-modal', [
                'material' => $material,
                'departments' => $this->departmentsForUser(),
                'projects' => $projects
            ]);
        }

        return view('admin.materials.edit', [
            'material' => $material,
            'departments' => $this->departmentsForUser(),
            'projects' => $projects
        ]);
    }

    public function update(Request $request, ManageMaterial $material): RedirectResponse
    {
        $this->authorizeEditHrRecord($material);

        $validated = $request->validate([
            'material_code' => 'required|string|max:255',
            'material_name' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'discipline' => 'required|string',
            'procurement_product' => 'required|string',
            'supplier_name' => 'required|string|max:255',
            'procurement_type' => 'required|string',
            'supply_route' => 'nullable|string',
            'approval' => 'nullable|string',
            'design_planned' => 'nullable|date',
            'design_actual' => 'nullable|date',
            'spec_preparation' => 'nullable|date',
            'request_order' => 'nullable|date',
            'supplier_identification' => 'nullable|date',
            'material_submittal' => 'nullable|date',
            'material_approval' => 'nullable|date',
            'finance' => 'nullable|date',
            'lc' => 'nullable|date',
            'payment' => 'nullable|date',
            'production_comp' => 'nullable|date',
            'shipment_of_material' => 'nullable|date',
            'logistics_to_dry_port' => 'nullable|date',
            'clearance_of_material' => 'nullable|date',
            'delivery_to_site' => 'nullable|date',
            'planned_start_date' => 'nullable|date',
            'production_days' => 'nullable|integer',
            'shipment_days' => 'nullable|integer',
        ]);

        $material->update($validated);

        return redirect()->route('admin.material.index')->with('success', 'Material record updated successfully.');
    }

    public function destroy(ManageMaterial $material): RedirectResponse
    {
        $this->authorizeDeleteHrRecord($material);

        $material->delete();

        return redirect()->route('admin.material.index')->with('success', 'Material record deleted successfully.');
    }

    private function scopeProjectManagementForUser($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isDceo()) {
            return $query;
        }

        if (auth()->user()->department?->name) {
            return $query->where('department', auth()->user()->department->name);
        }

        return $query->whereRaw('1 = 0');
    }

    private function departmentsForUser()
    {
        if (Schema::hasTable('project_sites')) {
            return ProjectSite::query()->orderBy('name')->pluck('name')->filter()->values();
        }

        return ManageMaterial::query()
            ->distinct('supplier_name')
            ->orderBy('supplier_name')
            ->pluck('supplier_name')
            ->filter()
            ->values();
    }

    private function authorizeCreateHrRecord(): void
    {
        // All authenticated users are allowed to submit material records.
    }

    private function authorizeViewHrRecord(ManageMaterial $material): void
    {
        if (auth()->user()->isAdmin() || auth()->user()->isDceo() || auth()->id() === $material->created_by) {
            return;
        }

        abort(403, 'Unauthorized to view this material record.');
    }

    private function authorizeEditHrRecord(ManageMaterial $material): void
    {
        if (auth()->user()->isAdmin() || auth()->user()->isDceo() || auth()->id() === $material->created_by) {
            return;
        }

        abort(403, 'Unauthorized to edit this material record.');
    }

    private function authorizeDeleteHrRecord(ManageMaterial $material): void
    {
        if (auth()->user()->isAdmin() || auth()->user()->isDceo() || auth()->id() === $material->created_by) {
            return;
        }

        abort(403, 'Unauthorized to delete this material record.');
    }
}
