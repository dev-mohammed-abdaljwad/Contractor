<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[\Database\Eloquent\Attributes\Fillable(['contractor_id', 'worker_id', 'company_id', 'deduction_date', 'type', 'amount', 'reason'])]
class Deduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'contractor_id',
        'worker_id',
        'company_id',
        'deduction_date',
        'type',
        'amount',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'deduction_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    // Relationships
    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
