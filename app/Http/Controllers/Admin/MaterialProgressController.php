<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManageMaterial;
use App\Services\MaterialProgressService;
use App\Services\MaterialReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaterialProgressController extends Controller
{
    protected $svc;
    protected $reportSvc;

    public function __construct(MaterialProgressService $svc, MaterialReportService $reportSvc)
    {
        $this->svc = $svc;
        $this->reportSvc = $reportSvc;
    }

    public function index(Request $request): View
    {
        $query = ManageMaterial::query()->with('project');

        if ($request->filled('discipline')) $query->where('discipline', $request->discipline);
        if ($request->filled('procurement_type')) $query->where('procurement_type', $request->procurement_type);
        if ($request->filled('supply_route')) $query->where('supply_route', $request->supply_route);

        $materials = $query->latest()->paginate(15)->withQueryString();

        $rows = $materials->getCollection()->map(function ($m) {
            $c = $this->svc->computeForMaterial($m);
            return [
                'id' => $m->id,
                'material_name' => $m->material_name,
                'supplier_name' => $m->supplier_name,
                'discipline' => $m->discipline,
                'procurement_type' => $m->procurement_type,
                'supply_route' => $m->supply_route,
                'project_code' => $m->project?->project_code ?? '—',
                'planned_stage' => $c['current_planned_stage'] ?? 'No Planned Dates',
                'actual_stage' => $c['current_actual_stage'] ?? 'No Actual Progress',
                'balanced_delay' => $c['balanced_delay'] ?? null,
                'delivery_float' => $c['delivery_float'] ?? null,
                'percent_complete' => $c['percent_complete'] ?? 0,
                'float_days' => $c['float_days'] ?? null,
                'status' => $c['status'] ?? 'Not Started',
                'delivery_planned' => $c['delivery_planned']?->toDateString(),
                'delivery_forecast' => $c['delivery_forecast']?->toDateString(),
                'delivery_actual' => $c['delivery_actual']?->toDateString(),
            ];
        });

        $materials->setCollection($rows);

        $total = ManageMaterial::count();
        $completed = ManageMaterial::whereNotNull('delivery_to_site')->count();
        $delayed = 0;
        $avgCompletion = 0;

        $sample = ManageMaterial::latest()->limit(200)->get();
        $sum = 0; $cnt = 0;
        foreach ($sample as $m) {
            $c = $this->svc->computeForMaterial($m);
            $sum += ($c['percent_complete'] ?? 0);
            $cnt++;
            if (($c['status'] ?? '') === 'Delayed') $delayed++;
        }
        $avgCompletion = $cnt ? (int) round($sum / $cnt) : 0;

        return view('admin.materials.progress', [
            'materials' => $materials,
            'total' => $total,
            'completed' => $completed,
            'delayed' => $delayed,
            'active' => max(0, $total - $completed),
            'avgCompletion' => $avgCompletion,
        ]);
    }

    public function show(ManageMaterial $material)
    {
        $data = $this->svc->computeForMaterial($material);
        $material->load('project');
        return response()->json([
            'material' => $material,
            'computed' => $data,
            'project_code' => $material->project?->project_code ?? '—',
            'planned_stage' => $data['current_planned_stage'] ?? 'No Planned Dates',
            'actual_stage' => $data['current_actual_stage'] ?? 'No Actual Progress',
            'balanced_delay' => $data['balanced_delay'] ?? null,
            'delivery_float' => $data['delivery_float'] ?? null,
        ]);
    }

    public function report(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $report = $this->reportSvc->buildReport($filter);

        $projects = ManageMaterial::select('project_id')->distinct()->count();
        $suppliers = ManageMaterial::select('supplier_name')->distinct()->count();

        return view('admin.materials.report', array_merge($report, [
            'projects_count' => $projects,
            'suppliers_count' => $suppliers,
            'current_filter' => $filter,
        ]));
    }
}
