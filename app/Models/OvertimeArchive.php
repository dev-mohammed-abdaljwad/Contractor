<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OvertimeArchive extends Model
{
    use SoftDeletes;

    protected $table = 'overtime_archives';

    protected $fillable = [
        'worker_id',
        'payment_id',
        'contractor_id',
        'week_start',
        'week_end',
        'total_overtime_hours',
        'total_overtime_amount',
        'daily_records',
    ];

    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
        'total_overtime_hours' => 'decimal:1',
        'total_overtime_amount' => 'decimal:2',
        'daily_records' => 'array',
    ];

    /**
     * Get the worker
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    /**
     * Get the payment
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the contractor
     */
    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }
}
