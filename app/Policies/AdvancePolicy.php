<?php

namespace App\Policies;

use App\Models\Advance;
use App\Models\User;

class AdvancePolicy
{
    public function view(User $user, Advance $advance): bool
    {
        return $user->id === $advance->contractor_id;
    }

    public function update(User $user, Advance $advance): bool
    {
        return $user->id === $advance->contractor_id && !$advance->is_fully_collected;
    }

    public function delete(User $user, Advance $advance): bool
    {
        return $user->id === $advance->contractor_id && !$advance->is_fully_collected;
    }
}
