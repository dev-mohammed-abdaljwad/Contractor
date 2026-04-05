<?php

namespace App\Repositories;

use App\Models\Collection;
use App\Repositories\Interfaces\CollectionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class CollectionRepository implements CollectionRepositoryInterface
{
    public function getByCompanyAndPeriod(int $companyId, string $from, string $to): EloquentCollection
    {
        return Collection::where('company_id', $companyId)
            ->whereBetween('period_start', [$from, $to])
            ->get();
    }

    public function getByContractor(int $contractorId): EloquentCollection
    {
        return Collection::where('contractor_id', $contractorId)->get();
    }

    public function create(array $data): Collection
    {
        return Collection::create($data);
    }

    public function update(int $id, array $data): Collection
    {
        $collection = Collection::findOrFail($id);
        $collection->update($data);
        return $collection;
    }

    public function findById(int $id): ?Collection
    {
        return Collection::find($id);
    }
}
