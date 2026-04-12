<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[\Database\Eloquent\Attributes\Fillable(['contractor_id', 'name', 'phone', 'national_id', 'joined_date', 'is_active'])]
class Worker extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contractor_id',
        'name',
        'phone',
        'national_id',
        'joined_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'joined_date' => 'date',
        ];
    }

    // Relationships
    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function distributions()
    {
        return $this->belongsToMany(DailyDistribution::class, 'distribution_worker', 'worker_id', 'distribution_id')
            ->withTimestamps();
    }

    public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }

    public function advances()
    {
        return $this->hasMany(Advance::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function overtimeArchives()
    {
        return $this->hasMany(OvertimeArchive::class);
    }
}
