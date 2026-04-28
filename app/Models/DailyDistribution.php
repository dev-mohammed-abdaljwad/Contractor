<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class DailyDistribution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contractor_id',
        'distribution_date',
        'company_id',
        'total_amount',
        'overtime_hours',
        'overtime_rate',
        'worker_daily_wage',
    ];

    protected function casts(): array
    {
        return [
            'distribution_date'  => 'date:Y-m-d',
            'total_amount'       => 'decimal:2',
            'overtime_hours'     => 'decimal:1',
            'overtime_rate'      => 'decimal:2',
            'worker_daily_wage'  => 'decimal:2',
            'created_at'         => 'datetime',
            'updated_at'         => 'datetime',
            'deleted_at'         => 'datetime',
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

    public function workers(): BelongsToMany
    {
        return $this->belongsToMany(Worker::class, 'distribution_worker', 'distribution_id', 'worker_id')
            ->withTimestamps();
    }

    public function actionLogs()
    {
        return $this->hasMany(DistributionActionLog::class, 'daily_distribution_id');
    }

    /**
     * Check if distribution can be edited (within 7 days)
     */
    public function canEdit(): bool
    {
        if ($this->created_at === null) {
            return true; // New distributions can be edited
        }
        return $this->created_at->addDays(7)->isFuture();
    }

    /**
     * What the company pays the contractor per worker per day.
     * Read from the related company model.
     */
    public function getCompanyDailyWageAttribute(): float
    {
        return (float) ($this->company->daily_wage ?? 0);
    }

    /**
     * Base cost for one worker in this distribution (before deductions are applied).
     */
    public function getWorkerCostAttribute(): float
    {
        return (float) ($this->worker_daily_wage ?? 0);
    }

    /**
     * Contractor's raw profit per worker before deducting overtime.
     * gross_profit_per_worker = company_daily_wage - worker_cost
     */
    public function getProfitPerWorkerAttribute(): float
    {
        return $this->company_daily_wage - $this->worker_cost;
    }

    /**
     * Total overtime paid to workers from this distribution.
     */
    public function getTotalOvertimeCostAttribute(): float
    {
        return (float) ($this->overtime_hours ?? 0) * (float) ($this->overtime_rate ?? 0);
    }

    /**
     * Net profit per worker after subtracting overtime cost.
     */
    public function getNetProfitPerWorkerAttribute(): float
    {
        return $this->profit_per_worker - $this->total_overtime_cost;
    }

    /**
     * Get overtime amount (overtime_hours * overtime_rate)
     *
     * @deprecated Use total_overtime_cost instead for clarity.
     */
    public function getOvertimeAmountAttribute(): float
    {
        return (float) ($this->overtime_hours ?? 0) * (float) ($this->overtime_rate ?? 0);
    }

    /**
     * Get total amount including overtime
     */
    public function getTotalWithOvertimeAttribute(): float
    {
        return (float) ($this->total_amount ?? 0) + $this->overtime_amount;
    }

    /**
     * Scope: Get distributions with overtime
     */
    public function scopeWithOvertime($query)
    {
        return $query->where('overtime_hours', '>', 0);
    }
}

