<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Worker;

class WorkerPolicy
{
    public function view(User $user, Worker $worker): bool
    {
        return $user->id === $worker->contractor_id;
    }

    public function update(User $user, Worker $worker): bool
    {
        return $user->id === $worker->contractor_id;
    }

    public function delete(User $user, Worker $worker): bool
    {
        return $user->id === $worker->contractor_id;
    }
}
