<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectWeeklyUpdate extends Model
{
    protected $fillable = [
        'project_id',
        'contact_person',
        'responsible_person',
        'expected_completion_date',
        'remaining_items',
        'status',
        'activity_planned_next_week',
        'constraints',
    ];

    protected $casts = [
        'expected_completion_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
