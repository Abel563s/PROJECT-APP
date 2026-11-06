<?php

namespace App\Models;

use Carbon\Carbon;
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
        'detail_reason_for_eot',
        'project_case_event_description',
        'reference_contract_clause_no',
        'affected_activities',
        'itc_notification_status',
        'itc_notification_date',
        'eot_status',
        'eot_submitted_date',
        'eot_response_date',
        'eot_requested_days',
        'eot_approved_days',
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
        'most_likely_quality_impact_description',
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
        'action_owner',
        'planned_action_date',
        'last_review_date',
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
        'last_review_date' => 'date',
        'date_last_updated' => 'date',
        'materialized_date' => 'date',
        'actual_action_date' => 'date',
        'itc_notification_date' => 'date',
        'eot_submitted_date' => 'date',
        'eot_response_date' => 'date',
        'eot_requested_days' => 'integer',
        'eot_approved_days' => 'integer',
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
    public const RESPONSE_STRATEGIES = [
        'Threat' => ['Avoid', 'Mitigate', 'Transfer', 'Accept', 'Escalate', 'Eliminate'],
        'Opportunity' => ['Exploit', 'Enhance', 'Share'],
    ];

    public static function calculateExposureDays(mixed $materializedDate, mixed $actualActionDate): ?int
    {
        if (!$materializedDate) {
            return null;
        }

        $start = Carbon::parse($materializedDate)->startOfDay();
        $end = $actualActionDate
            ? Carbon::parse($actualActionDate)->startOfDay()
            : Carbon::today();

        return (int) $start->diffInDays($end, false);
    }

    public function getCalculatedExposureDaysAttribute(): ?int
    {
        return static::calculateExposureDays($this->materialized_date, $this->actual_action_date);
    }

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
