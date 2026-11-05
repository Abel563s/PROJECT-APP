<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EotEntry extends Model
{
    protected $fillable = [
        'eot_id',
        'eot_category_id',
        'contract_reference_id',
        'detail_reason',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function baseOfRisk(): BelongsTo
    {
        return $this->belongsTo(EotCategory::class, 'eot_category_id');
    }

    public static function suggestNextId(EotCategory $category): string
    {
        $prefix = max(1, (int) $category->display_order);
        $max = static::query()->where('eot_category_id', $category->id)
            ->pluck('eot_id')->reduce(function (int $highest, string $eotId) use ($prefix): int {
                if (preg_match('/^'.preg_quote((string) $prefix, '/').'\.(\d+)$/', $eotId, $matches)) {
                    return max($highest, (int) $matches[1]);
                }

                return $highest;
            }, 0);

        return $prefix.'.'.($max + 1);
    }
}
