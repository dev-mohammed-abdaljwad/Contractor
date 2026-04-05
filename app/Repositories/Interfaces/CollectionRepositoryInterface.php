<?php

namespace App\Repositories\Interfaces;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface CollectionRepositoryInterface
{
    public function getByCompanyAndPeriod(int $companyId, string $from, string $to): EloquentCollection;
    
    public function getByContractor(int $contractorId): EloquentCollection;
    
    public function create(array $data): Collection;
    
    public function update(int $id, array $data): Collection;
    
    public function findById(int $id): ?Collection;
}
