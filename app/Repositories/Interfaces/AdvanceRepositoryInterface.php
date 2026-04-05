<?php

namespace App\Repositories\Interfaces;

use App\Models\Advance;
use Illuminate\Database\Eloquent\Collection;

interface AdvanceRepositoryInterface
{
    public function create(array $data): Advance;
    
    public function findById(int $id): ?Advance;
    
    public function update(int $id, array $data): Advance;
    
    public function getByWorker(int $workerId): Collection;
    
    public function getUnsettledByContractor(int $contractorId): Collection;
}
