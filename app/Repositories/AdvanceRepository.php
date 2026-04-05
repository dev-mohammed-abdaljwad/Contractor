<?php

namespace App\Repositories;

use App\Models\Advance;
use App\Repositories\Interfaces\AdvanceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AdvanceRepository implements AdvanceRepositoryInterface
{
    public function create(array $data): Advance
    {
        return Advance::create($data);
    }

    public function findById(int $id): ?Advance
    {
        return Advance::find($id);
    }

    public function update(int $id, array $data): Advance
    {
        $advance = Advance::findOrFail($id);
        $advance->update($data);
        return $advance;
    }

    public function getByWorker(int $workerId): Collection
    {
        return Advance::where('worker_id', $workerId)->get();
    }

    public function getUnsettledByContractor(int $contractorId): Collection
    {
        return Advance::where('contractor_id', $contractorId)
            ->where('is_settled', false)
            ->get();
    }
}
