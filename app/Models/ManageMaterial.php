<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageMaterial extends Model
{
    use HasFactory;

    protected $table = 'manage_materials';

    protected $fillable = [
        'material_code',
        'material_name',
        'project_id',
        'supplier_name',
        'discipline',
        'procurement_product',
        'supply_route',
        'approval',
        'procurement_type',
        'design_planned',
        'design_actual',
        'spec_preparation',
        'request_order',
        'supplier_identification',
        'material_submittal',
        'material_approval',
        'finance',
        'lc',
        'production_comp',
        'shipment_of_material',
        'logistics_to_dry_port',
        'clearance_of_material',
        'delivery_to_site',
        'planned_start_date',
        'production_days',
        'shipment_days',
        'payment',
        'created_by',
    ];

    protected $casts = [
        'design_planned' => 'date',
        'design_actual' => 'date',
        'spec_preparation' => 'date',
        'request_order' => 'date',
        'supplier_identification' => 'date',
        'material_submittal' => 'date',
        'material_approval' => 'date',
        'finance' => 'date',
        'lc' => 'date',
        'production_comp' => 'date',
        'shipment_of_material' => 'date',
        'logistics_to_dry_port' => 'date',
        'clearance_of_material' => 'date',
        'delivery_to_site' => 'date',
        'planned_start_date' => 'date',
        'production_days' => 'integer',
        'shipment_days' => 'integer',
        'payment' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getProjectCodeAttribute()
    {
        return $this->attributes['material_code'] ?? null;
    }

    public function getProjectNameAttribute()
    {
        return $this->attributes['material_name'] ?? null;
    }

    public function getProjectClientAttribute()
    {
        return $this->attributes['supplier_name'] ?? null;
    }

    public function getProjectTypeAttribute()
    {
        return $this->attributes['discipline'] ?? null;
    }

    public function getDeliveryMethodAttribute()
    {
        return $this->attributes['procurement_product'] ?? null;
    }

    public function getConsultancySectorAttribute()
    {
        return $this->attributes['procurement_type'] ?? null;
    }

    public function getScopeAttribute()
    {
        return $this->attributes['supply_route'] ?? null;
    }

    public function getApprovalStatusAttribute()
    {
        return $this->attributes['approval'] ?? null;
    }

    public function getProductionDaysAttribute()
    {
        return $this->attributes['production_days'] ?? null;
    }

    public function getShipmentDaysAttribute()
    {
        return $this->attributes['shipment_days'] ?? null;
    }
}
