<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[\Database\Eloquent\Attributes\Fillable(['contractor_id', 'company_id', 'period_start', 'period_end', 'total_days_worked', 'total_wages', 'total_deductions', 'net_amount', 'payment_method', 'payment_date', 'is_paid', 'notes'])]
class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'contractor_id',
        'company_id',
        'period_start',
        'period_end',
        'total_days_worked',
        'total_wages',
        'total_deductions',
        'net_amount',
        'payment_method',
        'payment_date',
        'is_paid',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'payment_date' => 'date',
            'total_wages' => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'is_paid' => 'boolean',
        ];
    }

    // Relationships
    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
