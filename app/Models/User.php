<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'phone', 'phone_secondary', 'email', 'avatar', 'role', 'plan', 'city', 'status', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    // Relationships
    public function companies()
    {
        return $this->hasMany(Company::class, 'contractor_id');
    }

    public function workers()
    {
        return $this->hasMany(Worker::class, 'contractor_id');
    }

    public function distributions()
    {
        return $this->hasMany(DailyDistribution::class, 'contractor_id');
    }

    public function deductions()
    {
        return $this->hasMany(Deduction::class, 'contractor_id');
    }

    public function advances()
    {
        return $this->hasMany(Advance::class, 'contractor_id');
    }

    public function collections()
    {
        return $this->hasMany(Collection::class, 'contractor_id');
    }

    public function preferences()
    {
        return $this->hasOne(UserPreference::class)->withDefault();
    }

    /**
     * Get user's full name (alias for name attribute).
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    // Scopes
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isContractor(): bool
    {
        return $this->role === 'contractor';
    }
}
