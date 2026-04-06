<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[\Database\Eloquent\Attributes\Fillable(['contractor_id', 'daily_distribution_id', 'action', 'reason', 'old_data', 'new_data'])]
class DistributionActionLog extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'old_data' => 'array',
            'new_data' => 'array',
        ];
    }

    // Relationships
    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function distribution()
    {
        return $this->belongsTo(DailyDistribution::class, 'daily_distribution_id');
    }
}
