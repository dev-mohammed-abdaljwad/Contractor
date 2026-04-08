<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'worker_id',
        'contractor_id',
        'amount',
        'date',
        'recovery_method',
        'reason',
        'installment_period',
        'installment_count',
        'installment_amount',
        'amount_collected',
        'amount_pending',
        'is_fully_collected',
        'fully_collected_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'amount_collected' => 'decimal:2',
        'amount_pending' => 'decimal:2',
        'is_fully_collected' => 'boolean',
        'fully_collected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function installments(): HasMany
    {
        return $this->hasMany(InstallmentSchedule::class);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('is_fully_collected', false);
    }

    public function scopeCollected($query)
    {
        return $query->where('is_fully_collected', true);
    }

    public function scopeForWorker($query, int $workerId)
    {
        return $query->where('worker_id', $workerId);
    }

    public function scopeForContractor($query, int $contractorId)
    {
        return $query->where('contractor_id', $contractorId);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
            ->whereYear('date', now()->year);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);
    }

    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('date', [$from, $to]);
    }

    public function scopeWithRelations($query)
    {
        return $query->select('advances.*')
            ->with([
                'worker:id,name,phone',
                'contractor:id,name,phone',
                'installments' => function ($q) {
                    $q->orderBy('installment_number');
                }
            ]);
    }

    /**
     * Accessors
     */
    public function getRecoveryStatusAttribute(): string
    {
        if ($this->is_fully_collected) {
            return 'مكتمل';
        }
        if ($this->amount_collected > 0) {
            return 'جزئي';
        }
        return 'معلق';
    }

    public function getPendingPercentageAttribute(): float
    {
        return $this->amount > 0 ? ($this->amount_pending / $this->amount) * 100 : 0;
    }
}
