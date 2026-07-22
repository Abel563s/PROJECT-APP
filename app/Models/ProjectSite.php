<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'created_by',
    ];
}
