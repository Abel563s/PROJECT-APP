<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Issue extends Model
{
    protected $fillable = [
        'project_id',
        'sector',
        'project_code',
        'issue_type',
        'issue',
        'description',
        'area_affected',
        'impact',
        'status',
        'raised_date',
        'raised_by',
        'action_plan',
        'planned_resolution_date',
        'action_by',
        'last_updated_date',
        'issue_closed_date',
        'remark',
        'date',
    ];

    protected $casts = [
        'raised_date' => 'date',
        'planned_resolution_date' => 'date',
        'last_updated_date' => 'datetime',
        'issue_closed_date' => 'date',
        'date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
