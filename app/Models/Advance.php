<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[\Database\Eloquent\Attributes\Fillable(['contractor_id', 'worker_id', 'amount', 'advance_date', 'notes', 'is_settled', 'settled_date'])]
class Advance extends Model
{
    use HasFactory;

    protected $fillable = [
        'contractor_id',
        'worker_id',
        'amount',
        'advance_date',
        'notes',
        'is_settled',
        'settled_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'advance_date' => 'date',
            'is_settled' => 'boolean',
            'settled_date' => 'date',
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
}
