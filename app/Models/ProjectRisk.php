<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectRisk extends Model
{
    protected $fillable = [
        'project_id',
        'created_by',
        'risk_category',
        'pool_risk_category',
        'pool_risk_id',
        'eot_entry_id',
        'activity',
        'risk_type',
        'risk_name',
        'risk_description',
        'likelihood',
        'severity',
        'project_impact',
        'intent_to_claim',
        'most_likely_cost_impact',
        'most_likely_time_impact',
        'most_likely_quality_impact',
        'time_impact_unit',
        'resolution_by',
        'risk_frequency',
        'impact_date',
        'risk_urgency',
        'existing_control',
        'residual_risk',
        'response_strategy',
        'mitigation_plan',
        'contingency_plan',
        'risk_owner',
        'owner_name',
        'planned_action_date',
        'date_last_updated',
        'materialized_date',
        'tracking_comments',
        'action_status',
        'actual_action_date',
        'exposure',
        'exposure_unit',
        'recommendation',
    ];

    protected $casts = [
        'likelihood' => 'integer',
        'severity' => 'integer',
        'intent_to_claim' => 'boolean',
        'most_likely_cost_impact' => 'decimal:2',
        'most_likely_time_impact' => 'integer',
        'most_likely_quality_impact' => 'integer',
        'exposure' => 'integer',
        'impact_date' => 'date',
        'planned_action_date' => 'date',
        'date_last_updated' => 'date',
        'materialized_date' => 'date',
        'actual_action_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function eotEntry(): BelongsTo
    {
        return $this->belongsTo(EotEntry::class);
    }

    public const ACTION_STATUSES = ['Risk', 'Issue', 'Resolved'];

    public function getRiskScoreAttribute(): int
    {
        return (int) $this->likelihood * (int) $this->severity;
    }

    public function getRiskLevelAttribute(): string
    {
        return match (true) {
            $this->risk_score >= 17 => 'Extreme',
            $this->risk_score >= 10 => 'High',
            $this->risk_score >= 5 => 'Medium',
            default => 'Low',
        };
    }
}
