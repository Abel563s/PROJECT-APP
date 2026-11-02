<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EotCategory extends Model
{
    protected $fillable = [
        'name',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
