<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[\Database\Eloquent\Attributes\Fillable(['contractor_id', 'name', 'contact_person', 'phone', 'daily_wage', 'payment_cycle', 'weekly_pay_day', 'contract_start_date', 'notes', 'is_active'])]
class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contractor_id',
        'name',
        'contact_person',
        'phone',
        'daily_wage',
        'payment_cycle',
        'weekly_pay_day',
        'contract_start_date',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'contract_start_date' => 'date',
            'is_active' => 'boolean',
            'daily_wage' => 'decimal:2',
        ];
    }

    // Relationships
    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function workers()
    {
        // Get workers through the distribution_worker pivot table
        return $this->hasManyThrough(
            Worker::class,
            DailyDistribution::class,
            'company_id', // Foreign key on daily_distributions
            'id',         // Foreign key on workers
            'id',         // Local key on companies
            'id'          // Local key on daily_distributions
        );
    }

    public function distributions()
    {
        return $this->hasMany(DailyDistribution::class);
    }

    public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function payments()
    {
        return $this->hasMany(CompanyPayment::class);
    }
}
