<?php

namespace App\Services;

use App\Models\ManageMaterial;
use Carbon\Carbon;

class MaterialProgressService
{
    protected static $procurementTypeSupplierDays = [
        'LVA' => 4,
        'RFQ-Local' => 6,
        'RFQ-Import-Birr' => 13,
        'RFQ-Import-USD' => 21,
        'IFB-ICB' => 34,
        'IFB-NCB' => 27,
        'RFP' => 29,
        'LTA' => 28,
    ];

    protected static $procurementTypePaymentDays = [
        'LVA' => 2,
        'RFQ-Local' => 4,
        'RFQ-Import-Birr' => 27,
        'RFQ-Import-USD' => 27,
        'IFB-ICB' => 33,
        'IFB-NCB' => 33,
        'RFP' => 33,
        'LTA' => 34,
    ];

    public function computeForMaterial(ManageMaterial $m): array
    {
        $out = [];

        $getDate = fn($d) => $d ? Carbon::parse($d)->startOfDay() : null;

        $plannedStart = $getDate($m->planned_start_date);
        $designPlanned = $getDate($m->design_planned);
        $designActual = $getDate($m->design_actual);

        $addDays = function ($base, $days) {
            if (!$base) return null;
            return Carbon::instance($base)->copy()->addDays($days);
        };

        $procType = $m->procurement_type ?? '';
        $supplierDays = static::$procurementTypeSupplierDays[$procType] ?? 0;
        $paymentDays = static::$procurementTypePaymentDays[$procType] ?? 0;
        $approvalDays = (strtolower($m->approval ?? '') === 'yes') ? 7 : 0;

        $out['design_planned'] = $designPlanned;
        $out['design_actual'] = $designActual;
        $out['design_forecast'] = $designPlanned;
        $out['design_variance'] = $this->daysBetween($designPlanned, $designActual);

        $out['spec_planned'] = $designPlanned ? $addDays($designPlanned, 5) : null;
        $out['spec_actual'] = $getDate($m->spec_preparation);
        $out['spec_forecast'] = $out['spec_planned'];
        $out['spec_variance'] = $this->daysBetween($out['spec_planned'], $out['spec_actual']);

        $out['ro_planned'] = $out['spec_planned'] ? $addDays($out['spec_planned'], 5) : null;
        $out['ro_actual'] = $getDate($m->request_order);
        $out['ro_forecast'] = $out['ro_planned'];
        $out['ro_variance'] = $this->daysBetween($out['ro_planned'], $out['ro_actual']);

        $out['supplier_planned'] = $out['ro_planned'] ? $addDays($out['ro_planned'], $supplierDays) : null;
        $out['supplier_actual'] = $getDate($m->supplier_identification);
        $out['supplier_forecast'] = $out['ro_forecast'] ? $addDays($out['ro_forecast'], $supplierDays) : null;
        $out['supplier_variance'] = $this->daysBetween($out['supplier_planned'], $out['supplier_actual']);

        $out['submittal_planned'] = $out['supplier_planned'] ? $addDays($out['supplier_planned'], 3) : null;
        $out['submittal_actual'] = $getDate($m->material_submittal);
        $out['submittal_forecast'] = $out['supplier_forecast'] ? $addDays($out['supplier_forecast'], 3) : null;
        $out['submittal_variance'] = $this->daysBetween($out['submittal_planned'], $out['submittal_actual']);

        $out['approval_planned'] = $out['submittal_planned'] ? $addDays($out['submittal_planned'], $approvalDays) : null;
        $out['approval_actual'] = $getDate($m->material_approval);
        $out['approval_forecast'] = $out['submittal_forecast'] ? $addDays($out['submittal_forecast'], $approvalDays) : null;
        $out['approval_variance'] = $this->daysBetween($out['approval_planned'], $out['approval_actual']);

        $out['payment_planned'] = $out['approval_planned'] ? $addDays($out['approval_planned'], $paymentDays) : null;
        $out['payment_actual'] = $getDate($m->payment);
        $out['payment_forecast'] = $out['approval_forecast'] ? $addDays($out['approval_forecast'], $paymentDays) : null;
        $out['payment_variance'] = $this->daysBetween($out['payment_planned'], $out['payment_actual']);

        $isImport = strtolower($m->supply_route ?? '') === 'import';

        if ($isImport) {
            $out['lc_planned'] = $out['payment_planned'] ? $addDays($out['payment_planned'], 21) : null;
            $out['lc_actual'] = $getDate($m->lc);
            $out['lc_forecast'] = $out['payment_forecast'] ? $addDays($out['payment_forecast'], 21) : null;
            $out['lc_variance'] = $this->daysBetween($out['lc_planned'], $out['lc_actual']);

            $prodDays = (int) ($m->production_days ?? 0);
            $out['production_planned'] = $out['lc_planned'] ? $addDays($out['lc_planned'], $prodDays) : null;
            $out['production_actual'] = $getDate($m->production_comp);
            $out['production_forecast'] = $out['lc_forecast'] ? $addDays($out['lc_forecast'], $prodDays) : null;
            $out['production_variance'] = $this->daysBetween($out['production_planned'], $out['production_actual']);

            $shipDays = (int) ($m->shipment_days ?? 0);
            $out['shipment_planned'] = $out['production_planned'] ? $addDays($out['production_planned'], $shipDays) : null;
            $out['shipment_actual'] = $getDate($m->shipment_of_material);
            $out['shipment_forecast'] = $out['production_forecast'] ? $addDays($out['production_forecast'], $shipDays) : null;
            $out['shipment_variance'] = $this->daysBetween($out['shipment_planned'], $out['shipment_actual']);

            $out['logistics_planned'] = $out['shipment_planned'] ? $addDays($out['shipment_planned'], 7) : null;
            $out['logistics_actual'] = $getDate($m->logistics_to_dry_port);
            $out['logistics_forecast'] = $out['shipment_forecast'] ? $addDays($out['shipment_forecast'], 7) : null;
            $out['logistics_variance'] = $this->daysBetween($out['logistics_planned'], $out['logistics_actual']);

            $out['clearance_planned'] = $out['logistics_planned'] ? $addDays($out['logistics_planned'], 7) : null;
            $out['clearance_actual'] = $getDate($m->clearance_of_material);
            $out['clearance_forecast'] = $out['logistics_forecast'] ? $addDays($out['logistics_forecast'], 7) : null;
            $out['clearance_variance'] = $this->daysBetween($out['clearance_planned'], $out['clearance_actual']);

            $out['delivery_planned'] = $out['clearance_planned'] ? $addDays($out['clearance_planned'], 1) : null;
            $out['delivery_forecast'] = $out['clearance_forecast'] ? $addDays($out['clearance_forecast'], 1) : null;
        } else {
            $out['lc_planned'] = $out['lc_actual'] = $out['lc_forecast'] = null;
            $out['production_planned'] = $out['production_actual'] = $out['production_forecast'] = null;
            $out['shipment_planned'] = $out['shipment_actual'] = $out['shipment_forecast'] = null;
            $out['logistics_planned'] = $out['logistics_actual'] = $out['logistics_forecast'] = null;
            $out['clearance_planned'] = $out['clearance_actual'] = $out['clearance_forecast'] = null;

            $out['delivery_planned'] = $out['payment_planned'] ? $addDays($out['payment_planned'], 1) : null;
            $out['delivery_forecast'] = $out['payment_forecast'] ? $addDays($out['payment_forecast'], 1) : null;
        }

        $out['delivery_actual'] = $getDate($m->delivery_to_site);
        $out['delivery_variance'] = $this->daysBetween($out['delivery_planned'], $out['delivery_actual']);
        $out['delivery_float'] = $this->signedDaysBetween($out['delivery_planned'], $out['delivery_actual']);

        $out['float_days'] = $this->daysBetween($plannedStart, $out['delivery_planned']);
        $out['total_variance'] = $out['delivery_variance'];

        $stageVariances = array_values(array_filter([
            $out['design_variance'] ?? null,
            $out['spec_variance'] ?? null,
            $out['ro_variance'] ?? null,
            $out['supplier_variance'] ?? null,
            $out['submittal_variance'] ?? null,
            $out['approval_variance'] ?? null,
            $out['payment_variance'] ?? null,
            $out['lc_variance'] ?? null,
            $out['production_variance'] ?? null,
            $out['shipment_variance'] ?? null,
            $out['logistics_variance'] ?? null,
            $out['clearance_variance'] ?? null,
            $out['delivery_variance'] ?? null,
        ]));

        $minVariance = !empty($stageVariances) ? min($stageVariances) : null;
        $float = $out['float_days'] ?? null;

        if ($float !== null && is_numeric($float) && $minVariance !== null) {
            $out['balanced_delay'] = $minVariance + $float;
        } else {
            $out['balanced_delay'] = null;
        }

        $stages = [
            ['key' => 'design', 'actual' => $out['design_actual']],
            ['key' => 'spec', 'actual' => $out['spec_actual']],
            ['key' => 'ro', 'actual' => $out['ro_actual']],
            ['key' => 'supplier', 'actual' => $out['supplier_actual']],
            ['key' => 'submittal', 'actual' => $out['submittal_actual']],
            ['key' => 'approval', 'actual' => $out['approval_actual']],
            ['key' => 'payment', 'actual' => $out['payment_actual']],
        ];

        if ($isImport) {
            $stages = array_merge($stages, [
                ['key' => 'lc', 'actual' => $out['lc_actual']],
                ['key' => 'production', 'actual' => $out['production_actual']],
                ['key' => 'shipment', 'actual' => $out['shipment_actual']],
                ['key' => 'logistics', 'actual' => $out['logistics_actual']],
                ['key' => 'clearance', 'actual' => $out['clearance_actual']],
            ]);
        }

        $stages[] = ['key' => 'delivery', 'actual' => $out['delivery_actual']];

        $totalStages = count($stages);
        $completed = 0;
        foreach ($stages as $s) {
            if ($s['actual']) $completed++;
        }

        $out['completed_stages'] = $completed;
        $out['total_stages'] = $totalStages;
        $out['percent_complete'] = $totalStages ? (int) round(($completed / $totalStages) * 100) : 0;

        if (!$completed) {
            $status = 'Not Started';
        } elseif ($out['delivery_actual']) {
            $status = 'Completed';
        } else {
            $delayed = false;
            foreach (['design_variance','spec_variance','ro_variance','supplier_variance','submittal_variance','approval_variance','payment_variance','lc_variance','production_variance','shipment_variance','logistics_variance','clearance_variance','delivery_variance'] as $v) {
                if (!empty($out[$v]) && $out[$v] > 0) { $delayed = true; break; }
            }
            $status = $delayed ? 'Delayed' : 'In Progress';
        }

        $out['status'] = $status;

        $plannedStages = [];
        if ($out['design_planned']) $plannedStages['Design'] = $out['design_planned'];
        if ($out['spec_planned']) $plannedStages['Specification Submitted'] = $out['spec_planned'];
        if ($out['ro_planned']) $plannedStages['RO Submitted'] = $out['ro_planned'];
        if ($out['supplier_planned']) $plannedStages['Supplier Identification'] = $out['supplier_planned'];
        if ($out['submittal_planned']) $plannedStages['Material Submittal'] = $out['submittal_planned'];
        if ($out['approval_planned']) $plannedStages['Material Approval'] = $out['approval_planned'];
        if ($out['payment_planned']) $plannedStages['Finance'] = $out['payment_planned'];
        if ($out['lc_planned']) $plannedStages['LC Opening'] = $out['lc_planned'];
        if ($out['production_planned']) $plannedStages['Production Completion'] = $out['production_planned'];
        if ($out['shipment_planned']) $plannedStages['Shipment of Material'] = $out['shipment_planned'];
        if ($out['logistics_planned']) $plannedStages['Logistics to Dry Port'] = $out['logistics_planned'];
        if ($out['clearance_planned']) $plannedStages['Clearance'] = $out['clearance_planned'];
        if ($out['delivery_planned']) $plannedStages['Delivery to Site'] = $out['delivery_planned'];

        if (empty($plannedStages)) {
            $out['current_planned_stage'] = 'No Planned Dates';
        } else {
            $today = Carbon::today();
            $upcoming = [];
            $past = [];
            foreach ($plannedStages as $name => $date) {
                if ($date->greaterThanOrEqualTo($today)) {
                    $upcoming[] = ['name' => $name, 'date' => $date, 'diff' => $date->diffInDays($today)];
                } else {
                    $past[] = ['name' => $name, 'date' => $date, 'diff' => $today->diffInDays($date)];
                }
            }

            if (!empty($upcoming)) {
                usort($upcoming, fn($a, $b) => $a['diff'] <=> $b['diff']);
                $out['current_planned_stage'] = $upcoming[0]['name'];
            } elseif (!empty($past)) {
                usort($past, fn($a, $b) => $a['diff'] <=> $b['diff']);
                $out['current_planned_stage'] = $past[0]['name'];
            } else {
                $out['current_planned_stage'] = 'No Planned Dates';
            }
        }

        $actualStageKeys = [
            'Design' => $out['design_actual'] ?? null,
            'Specification Submitted' => $out['spec_actual'] ?? null,
            'RO Submitted' => $out['ro_actual'] ?? null,
            'Supplier Identification' => $out['supplier_actual'] ?? null,
            'Material Submittal' => $out['submittal_actual'] ?? null,
            'Material Approval' => $out['approval_actual'] ?? null,
            'Finance' => $out['payment_actual'] ?? null,
            'LC Opening' => $out['lc_actual'] ?? null,
            'Production Completion' => $out['production_actual'] ?? null,
            'Shipment of Material' => $out['shipment_actual'] ?? null,
            'Logistics to Dry Port' => $out['logistics_actual'] ?? null,
            'Clearance' => $out['clearance_actual'] ?? null,
            'Delivery to Site' => $out['delivery_actual'] ?? null,
        ];

        $actualStageNames = array_keys($actualStageKeys);
        $currentActualStage = 'No Actual Progress';
        foreach ($actualStageKeys as $name => $actualDate) {
            if (!$actualDate) {
                $currentActualStage = $name;
                break;
            }
            $currentActualStage = $name;
        }

        $out['current_actual_stage'] = $currentActualStage;

        return $out;
    }

    protected function daysBetween($a, $b)
    {
        if (!$a || !$b) return null;
        return (int) Carbon::parse($b)->startOfDay()->diffInDays(Carbon::parse($a)->startOfDay());
    }

    protected function signedDaysBetween($a, $b)
    {
        if (!$a || !$b) return null;
        return (int) Carbon::parse($b)->startOfDay()->diffInDays(Carbon::parse($a)->startOfDay(), false);
    }
}
