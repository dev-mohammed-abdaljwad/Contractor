<?php

namespace App\Repositories;

use App\Models\Worker;
use App\Repositories\Interfaces\WorkerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WorkerRepository implements WorkerRepositoryInterface
{
    public function getAllByContractor(int $contractorId): Collection
    {
        return Worker::where('contractor_id', $contractorId)->get();
    }

    public function findById(int $id): ?Worker
    {
        return Worker::find($id);
    }

    public function create(array $data): Worker
    {
        return Worker::create($data);
    }

    public function update(int $id, array $data): Worker
    {
        $worker = Worker::findOrFail($id);
        $worker->update($data);
        return $worker;
    }

    public function getActiveWorkers(int $contractorId): Collection
    {
        return Worker::where('contractor_id', $contractorId)
            ->where('is_active', true)
            ->get();
    }
}
