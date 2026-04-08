<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'distribution_id',
        'contractor_id',
        'type',
        'amount',
        'reason',
        'is_reversed',
        'reversed_at',
        'reversed_by',
        'reversal_reason',
    ];

    protected function casts(): array
    {
        return [
            'type' => 'string',
            'amount' => 'decimal:2',
            'is_reversed' => 'boolean',
            'reversed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the worker associated with this deduction.
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    /**
     * Get the daily distribution for this deduction.
     */
    public function distribution(): BelongsTo
    {
        return $this->belongsTo(DailyDistribution::class);
    }

    /**
     * Get the contractor who recorded this deduction.
     */
    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    /**
     * Get the user who reversed this deduction.
     */
    public function reversedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reversed_by');
    }

    /**
     * Get the multiplier for the deduction type.
     */
    public function getTypeMultiplier(): float
    {
        return match ($this->type) {
            'quarter' => 0.25,
            'half' => 0.5,
            'full' => 1.0,
            default => 0.0,
        };
    }

    /**
     * Get the net amount that was deducted.
     */
    public function getNetAmount(): float
    {
        if (!$this->distribution || !$this->distribution->company) {
            return (float) $this->amount;
        }

        return (float) ($this->distribution->company->daily_wage * $this->getTypeMultiplier());
    }

    /**
     * Scope: Get only active (non-reversed) deductions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_reversed', false);
    }

    /**
     * Scope: Get only reversed deductions.
     */
    public function scopeReversed($query)
    {
        return $query->where('is_reversed', true);
    }

    /**
     * Scope: Filter by worker.
     */
    public function scopeForWorker($query, int $workerId)
    {
        return $query->where('worker_id', $workerId);
    }

    /**
     * Scope: Filter by current month.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereYear('created_at', now()->year)
                     ->whereMonth('created_at', now()->month);
    }

    /**
     * Scope: Filter by current week.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);
    }

    /**
     * Scope: Filter by custom date range.
     */
    public function scopeDateRange($query, Carbon $from, Carbon $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Scope: Eager load common relationships.
     */
    public function scopeWithRelations($query)
    {
        return $query->with([
            'worker',
            'distribution.company',
            'contractor:id,name,phone',
            'reversedBy:id,name,phone',
        ]);
    }
}
