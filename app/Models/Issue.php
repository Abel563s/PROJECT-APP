<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Issue extends Model
{
    protected $fillable = [
        'project_id',
        'created_by',
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

    public function getDaysLeftAttribute(): ?int
    {
        if (!$this->planned_resolution_date) {
            return null;
        }

        return (int) Carbon::today()->diffInDays($this->planned_resolution_date->copy()->startOfDay(), false);
    }

    public function getPriorityAttribute(): string
    {
        if ($this->status === 'Closed') {
            return 'Done';
        }

        if ($this->days_left === null) {
            return 'Unscheduled';
        }

        return match (true) {
            $this->days_left < 0 => 'Overdue',
            $this->days_left < 7 => 'High',
            $this->days_left < 14 => 'Medium',
            default => 'Low',
        };
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
