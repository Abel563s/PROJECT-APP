<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectRiskPool extends Model
{
    protected $fillable = [
        'risk_id',
        'risk_type',
        'risk_category',
        'specific_risk',
        'detailed_risk_explanation',
        'impact_on_timeline',
        'impact_on_timeline_description',
        'impact_on_cost',
        'impact_on_cost_description',
        'overall_impact_level',
        'impact_on_quality_description',
        'other_potential_impact',
        'stakeholder',
        'recommended_mitigation_measures',
        'created_by',
    ];

    public const IMPACT_LEVELS = ['High', 'Medium', 'Low'];

    public const RISK_TYPES = ['Opportunity', 'Threat'];

    public const CATEGORIES = [
        'Financial Risks',
        'Procurement Risks',
        'Labor Risks',
        'Design & Technical Risks',
        'Safety Risks',
        'Environmental Risks',
        'Legal & Regulatory Risks',
        'Operational/Fuel Risks',
        'Force Majure',
        'Schedule',
        'Strategic/ Corporate Risks',
    ];

    public const CATEGORY_PREFIXES = [
        'Financial Risks' => 1,
        'Procurement Risks' => 2,
        'Labor Risks' => 3,
        'Design & Technical Risks' => 4,
        'Safety Risks' => 5,
        'Environmental Risks' => 6,
        'Legal & Regulatory Risks' => 7,
        'Operational/Fuel Risks' => 8,
        'Force Majure' => 9,
        'Schedule' => 10,
        'Strategic/ Corporate Risks' => 11,
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Category this risk belongs to (resolved by name, so deactivated
     * categories are still retrievable for existing records).
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(RiskCategory::class, 'risk_category', 'name');
    }

    /**
     * Active category names, falling back to the hardcoded defaults when the
     * database has not been seeded yet.
     */
    public static function availableCategories(): array
    {
        $active = RiskCategory::activeNames();

        return $active !== [] ? $active : self::CATEGORIES;
    }

    public static function allCategories(): array
    {
        return RiskCategory::query()->orderBy('display_order')->orderBy('name')->pluck('name')->toArray()
            ?: self::CATEGORIES;
    }

    public static function categoryPrefix(string $category): ?string
    {
        $prefix = RiskCategory::resolvePrefix($category);

        return $prefix !== null ? (string) $prefix : null;
    }

    public static function suggestNextRiskId(string $category): ?string
    {
        $prefix = self::categoryPrefix($category);
        if ($prefix === null) {
            return null;
        }

        $maxSuffix = self::where('risk_id', 'like', $prefix.'.%')
            ->pluck('risk_id')
            ->reduce(function (int $carry, string $id) use ($prefix): int {
                if (! preg_match('/^'.preg_quote($prefix, '/').'\.(\d+)$/', $id, $matches)) {
                    return $carry;
                }

                $suffix = (int) $matches[1];

                return max($carry, $suffix);
            }, 0);

        return $prefix.'.'.str_pad((string) ($maxSuffix + 1), 2, '0', STR_PAD_LEFT);
    }

    public static function stats(): array
    {
        return [
            'total' => self::count(),
            'high' => self::where('overall_impact_level', 'High')->count(),
            'medium' => self::where('overall_impact_level', 'Medium')->count(),
            'low' => self::where('overall_impact_level', 'Low')->count(),
        ];
    }

    public function scopeOrdered($query, string $direction = 'asc')
    {
        $dir = $direction === 'desc' ? 'DESC' : 'ASC';
        $driver = $query->getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            $query->orderByRaw("CAST(SUBSTR(risk_id, 1, INSTR(risk_id, '.') - 1) AS INTEGER) $dir")
                ->orderByRaw("CAST(SUBSTR(risk_id, INSTR(risk_id, '.') + 1) AS INTEGER) $dir, risk_id $dir");
        } else {
            $query->orderByRaw("CAST(SUBSTRING_INDEX(risk_id, '.', 1) AS UNSIGNED) $dir")
                ->orderByRaw("CAST(SUBSTRING_INDEX(risk_id, '.', -1) AS UNSIGNED) $dir, risk_id $dir");
        }

        return $query;
    }

    public function scopeSearch($query, ?string $term)
    {
        if ($term === null || $term === '') {
            return $query;
        }

        $query->where(function ($q) use ($term) {
            $q->where('risk_id', 'like', "%{$term}%")
                ->orWhere('risk_type', 'like', "%{$term}%")
                ->orWhere('specific_risk', 'like', "%{$term}%")
                ->orWhere('risk_category', 'like', "%{$term}%")
                ->orWhere('stakeholder', 'like', "%{$term}%")
                ->orWhere('detailed_risk_explanation', 'like', "%{$term}%")
                ->orWhere('impact_on_timeline_description', 'like', "%{$term}%")
                ->orWhere('impact_on_cost_description', 'like', "%{$term}%")
                ->orWhere('impact_on_quality_description', 'like', "%{$term}%")
                ->orWhere('other_potential_impact', 'like', "%{$term}%");
        });

        return $query;
    }

    public function scopeApplyFilters($query, array $filters)
    {
        if (! empty($filters['risk_type']) && $filters['risk_type'] !== 'all') {
            $query->where('risk_type', $filters['risk_type']);
        }

        if (! empty($filters['risk_category']) && $filters['risk_category'] !== 'all') {
            $query->where('risk_category', $filters['risk_category']);
        }

        if (! empty($filters['overall_impact_level']) && $filters['overall_impact_level'] !== 'all') {
            $query->where('overall_impact_level', $filters['overall_impact_level']);
        }

        foreach (['impact_on_timeline', 'impact_on_cost'] as $field) {
            if (! empty($filters[$field]) && $filters[$field] !== 'all') {
                $query->where($field, $filters[$field]);
            }
        }

        return $query;
    }

    public function scopeApplySort($query, string $sort, string $direction = 'asc')
    {
        $dir = $direction === 'desc' ? 'desc' : 'asc';

        $allowed = ['risk_id', 'risk_category', 'specific_risk', 'overall_impact_level', 'impact_on_timeline', 'impact_on_cost', 'updated_at', 'created_at'];

        if (! in_array($sort, $allowed, true)) {
            $sort = 'risk_id';
        }

        if ($sort === 'risk_id') {
            return $query->ordered($direction);
        }

        if (in_array($sort, ['overall_impact_level', 'impact_on_timeline', 'impact_on_cost'], true)) {
            return $query->orderByRaw("CASE {$sort} WHEN 'High' THEN 1 WHEN 'Medium' THEN 2 WHEN 'Low' THEN 3 ELSE 4 END {$dir}, {$sort} {$dir}");
        }

        return $query->orderBy($sort, $dir);
    }
}
