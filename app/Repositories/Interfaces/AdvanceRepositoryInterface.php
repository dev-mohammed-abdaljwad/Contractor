<?php

namespace App\Repositories\Interfaces;

use App\Models\Advance;
use Illuminate\Database\Eloquent\Collection;

interface AdvanceRepositoryInterface
{
    public function create(array $data): Advance;
    
    public function findById(int $id): ?Advance;
    
    public function findByWorker(int $workerId): Collection;
    
    public function findPendingByWorker(int $workerId): Collection;
    
    public function update(int $id, array $data): bool;
    
    public function updateRecoveryMethod(int $id, array $data): bool;
    
    public function recordCollection(int $id, float $amount): bool;
    
    public function getMonthlyTotalForWorker(int $workerId): float;
    
    public function getPendingTotalForWorker(int $workerId): float;
    
    public function getCollectedTotalForWorker(int $workerId): float;
    
    public function getByContractor(int $contractorId, array $filters = []): Collection;
    
    public function getByPeriod($from, $to): Collection;
    
    public function getWorkersWithPendingAdvances(int $contractorId): Collection;
}

