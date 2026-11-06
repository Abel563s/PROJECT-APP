<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskCategory extends Model
{
    protected $fillable = [
        'name',
        'code',
        'risk_type',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'display_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }

    public function risks()
    {
        return $this->hasMany(ProjectRiskPool::class, 'risk_category', 'name');
    }

    /**
     * All active category names available for selection / validation.
     */
    public static function activeNames(): array
    {
        return self::query()->where('is_active', true)->orderBy('display_order')->orderBy('name')->pluck('name')->toArray();
    }

    /**
     * Resolve the numeric prefix used for Risk ID generation for a category.
     */
    public static function resolvePrefix(string $category): ?string
    {
        $code = self::where('name', $category)->value('code');
        if ($code !== null && $code !== '') {
            return ctype_digit((string) $code) ? (string) $code : null;
        }

        $fallback = ProjectRiskPool::CATEGORY_PREFIXES[$category] ?? null;

        return $fallback !== null ? (string) $fallback : null;
    }
}
