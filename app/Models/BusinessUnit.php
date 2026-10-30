<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sectors()
    {
        return $this->hasMany(Sector::class);
    }

    public function activeSectors()
    {
        return $this->hasMany(Sector::class)->where('is_active', true);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function activeUsers()
    {
        return $this->hasMany(User::class)->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
