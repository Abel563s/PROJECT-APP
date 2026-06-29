<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_id',
        'project_code',
        'project_name',
        'project_type',
        'delivery_method',
        'project_client',
        'consultant',
        'consultancy_sector',
        'scope',
        'contract_budget',
        'variation',
        'supplementary',
        'total_allowable_cost',
        'cost_at_completion',
        'baseline_start_date',
        'baseline_finish_date',
        'actual_start_date',
        'actual_finish_date',
        'approved_eot',
        'revision_number',
        'closing_status',
        'ppa_received_at',
        'pa_received_at',
        'fa_received_at',
        'location',
        'project_manager',
        'total_certified',
        'total_paid',
        'retention',
        'physical_completion_percent',
        'handover_date',
        'defects_liability_period',
        'snag_list_status',
        'completion_certificate_status',
        'doc_completion_cert_status',
        'doc_as_built_status',
        'doc_final_boq_status',
        'doc_handover_report_status',
        'challenges_faced',
        'financial_performance_notes',
        'schedule_performance_notes',
        'recommendations',
    ];

    protected $casts = [
        'baseline_start_date' => 'date',
        'baseline_finish_date' => 'date',
        'actual_start_date' => 'date',
        'actual_finish_date' => 'date',
        'approved_eot' => 'date',
        'ppa_received_at' => 'date',
        'pa_received_at' => 'date',
        'fa_received_at' => 'date',
        'handover_date' => 'date',
        'contract_budget' => 'decimal:2',
        'variation' => 'decimal:2',
        'supplementary' => 'decimal:2',
        'total_allowable_cost' => 'decimal:2',
        'cost_at_completion' => 'decimal:2',
        'total_certified' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'retention' => 'decimal:2',
        'physical_completion_percent' => 'decimal:2',
    ];

    // Accessors for calculated fields
    public function getOutstandingBalanceAttribute()
    {
        return $this->total_project_value - (float) $this->total_certified;
    }
    public function getTotalProjectValueAttribute()
    {
        return (float) $this->contract_budget + (float) $this->variation + (float) $this->supplementary;
    }

    public function getRevisedBudgetAttribute()
    {
        return $this->total_project_value;
    }

    public function getCostVarianceAttribute()
    {
        return $this->total_project_value - $this->cost_at_completion;
    }

    public function getBaselineDurationAttribute()
    {
        if ($this->baseline_start_date && $this->baseline_finish_date) {
            return $this->baseline_start_date->diffInDays($this->baseline_finish_date);
        }
        return 0;
    }

    public function getActualDurationAttribute()
    {
        $start = $this->actual_start_date;
        $finish = $this->actual_finish_date ?: now();

        if ($start) {
            return $start->diffInDays($finish);
        }
        return 0;
    }

    public function getDelayAttribute()
    {
        $delay = (int) $this->actual_duration - (int) $this->baseline_duration;
        return $delay;
    }

    public function getScheduleVarianceAttribute()
    {
        return $this->delay;
    }

    public function getDelayStatusAttribute()
    {
        $delay = $this->delay;
        if ($delay <= 0)
            return 'On Time';
        if ($delay <= 30)
            return 'Delayed';
        return 'Critical';
    }

    public function progressUpdates()
    {
        return $this->hasMany(ProjectProgressUpdate::class);
    }

    public function weeklyUpdates()
    {
        return $this->hasMany(ProjectWeeklyUpdate::class);
    }

    public function payments()
    {
        return $this->hasMany(ProjectPayment::class);
    }

    protected static function booted()
    {
        static::creating(function ($project) {
            if (!$project->custom_id) {
                $lastProject = static::orderBy('id', 'desc')->first();
                $nextId = $lastProject ? (int) str_replace('PRJ-', '', $lastProject->custom_id) + 1 : 1;
                $project->custom_id = 'PRJ-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });

        static::saving(function ($project) {
            $financialFields = [
                'contract_budget',
                'variation',
                'supplementary',
                'total_allowable_cost',
                'cost_at_completion',
                'total_certified',
                'total_paid',
                'retention',
                'physical_completion_percent'
            ];

            foreach ($financialFields as $field) {
                if ($project->$field === null) {
                    $project->$field = 0;
                }
            }
        });
    }
}
