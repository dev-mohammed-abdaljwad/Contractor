<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[\Database\Eloquent\Attributes\Fillable(['contractor_id', 'distribution_date', 'company_id', 'worker_id', 'daily_wage_snapshot'])]
class DailyDistribution extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'distribution_date' => 'date',
            'daily_wage_snapshot' => 'decimal:2',
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

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
