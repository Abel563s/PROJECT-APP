<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'certificate_number',
        'certificate_date',
        'submitted_amount',
        'submitted_date',
        'certified_amount',
        'certified_date',
        'amount_paid',
        'payment_date',
        'remarks',
    ];

    protected $casts = [
        'certificate_date' => 'date',
        'submitted_date' => 'date',
        'certified_date' => 'date',
        'payment_date' => 'date',
        'submitted_amount' => 'decimal:2',
        'certified_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getStatusAttribute()
    {
        $target = $this->certified_amount > 0 ? $this->certified_amount : ($this->submitted_amount > 0 ? $this->submitted_amount : 0);

        if ($target > 0 && $this->amount_paid >= $target) {
            return 'Paid';
        } elseif ($this->amount_paid > 0) {
            return 'Partially Paid';
        } else {
            return 'Pending';
        }
    }
}
