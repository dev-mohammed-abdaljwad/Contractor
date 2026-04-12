<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $table = 'user_preferences';

    protected $fillable = [
        'user_id',
        'notify_overdue_payments',
        'notify_daily_distribution',
        'notify_weekly_report',
        'notify_pending_advances',
        'language',
        'currency',
        'overtime_hourly_rate',
        'date_format',
        'week_start',
        'dark_mode',
    ];

    protected $casts = [
        'notify_overdue_payments' => 'boolean',
        'notify_daily_distribution' => 'boolean',
        'notify_weekly_report' => 'boolean',
        'notify_pending_advances' => 'boolean',
        'dark_mode' => 'boolean',
        'overtime_hourly_rate' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
