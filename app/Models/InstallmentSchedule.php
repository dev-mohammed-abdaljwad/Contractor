<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallmentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'advance_id',
        'installment_number',
        'amount',
        'due_date',
        'amount_paid',
        'paid_at',
        'is_paid',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'amount_paid' => 'decimal:2',
        'paid_at' => 'datetime',
        'is_paid' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function advance(): BelongsTo
    {
        return $this->belongsTo(Advance::class);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('is_paid', false);
    }

    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString())
            ->where('is_paid', false);
    }

    /**
     * Accessors
     */
    public function getRemainingAmountAttribute(): float
    {
        return (float) ($this->amount - $this->amount_paid);
    }

    public function getIsOverdueAttribute(): bool
    {
        return !$this->is_paid && $this->due_date < now()->toDateString();
    }
}
