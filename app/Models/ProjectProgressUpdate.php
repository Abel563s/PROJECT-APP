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
        'completion_date',
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
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
