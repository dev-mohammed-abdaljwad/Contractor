<?php

namespace App\Repositories\Interfaces;

use App\Models\DailyDistribution;
use Illuminate\Database\Eloquent\Collection;

interface DistributionRepositoryInterface
{
    public function getAllByContractor(int $contractorId): Collection;
    
    public function getByDateAndContractor(string $date, int $contractorId): Collection;
    
    public function getAssignedWorkerIdsForDate(string $date, int $contractorId): array;
    
    public function getByDateWithDeductions(string $date, int $contractorId): Collection;
    
    public function getByWorkerAndDate(int $workerId, string $date): ?DailyDistribution;
    
    public function getAssignedWorkerIdsFromList(array $workerIds, string $date): array;
    
    public function getByCompanyAndPeriod(int $companyId, string $from, string $to): Collection;
    
    public function getByWorkerAndPeriod(int $workerId, string $from, string $to): Collection;
    
    public function findByIdWithDetails(int $id): DailyDistribution;
    
    public function create(array $data): DailyDistribution;
    
    public function delete(int $id): void;
}
