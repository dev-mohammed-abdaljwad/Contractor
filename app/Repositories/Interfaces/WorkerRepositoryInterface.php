<?php

namespace App\Repositories\Interfaces;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Collection;

interface WorkerRepositoryInterface
{
    public function getAllByContractor(int $contractorId): Collection;
    
    public function getAllByContractorWithFullData(int $contractorId): Collection;
    
    public function findById(int $id): ?Worker;
    
    public function findByIdWithFullData(int $id): ?Worker;
    
    public function create(array $data): Worker;
    
    public function update(int $id, array $data): Worker;
    
    public function getActiveWorkers(int $contractorId): Collection;
}
