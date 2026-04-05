<?php

namespace App\Repositories\Interfaces;

use App\Models\DailyDistribution;
use Illuminate\Database\Eloquent\Collection;

interface DistributionRepositoryInterface
{
    public function getByDateAndContractor(string $date, int $contractorId): Collection;
    
    public function getByWorkerAndDate(int $workerId, string $date): ?DailyDistribution;
    
    public function getByCompanyAndPeriod(int $companyId, string $from, string $to): Collection;
    
    public function getByWorkerAndPeriod(int $workerId, string $from, string $to): Collection;
    
    public function create(array $data): DailyDistribution;
    
    public function delete(int $id): void;
}
