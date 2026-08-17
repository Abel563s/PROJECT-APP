<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProgressUpdate extends Model
{
    protected $fillable = [
        'project_id',
        'progress_planned',
        'progress_actual',
        'revenue_planned',
        'revenue_actual',
        'revenue_planned_weekly',
        'revenue_actual_weekly',
        'cost_planned',
        'cost_actual',
        'cost_planned_weekly',
        'cost_actual_weekly',
        'completion_date',
        'data_date',
        'top_constraints',
        'client_issue',
        'design_completion_approval',
        'material_submittal_approval',
        'material_delivery',
        'labor',
        'machinery_equipment',
        'subcontractor',
        'finance',
        'operation_constraint',
    ];

    protected $casts = [
        'completion_date' => 'date',
        'data_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
