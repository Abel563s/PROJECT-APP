<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractClause extends Model
{
    protected $fillable = [
        'row_number',
        'item',
        'ppa',
        'fidic_red',
        'fidic_yellow',
        'fidic_silver',
        'planner_explanation',
        'created_by',
    ];

    protected $casts = ['row_number' => 'integer'];
}
