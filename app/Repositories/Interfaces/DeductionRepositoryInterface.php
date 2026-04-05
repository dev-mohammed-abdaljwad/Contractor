<?php

namespace App\Repositories\Interfaces;

use App\Models\Deduction;
use Illuminate\Database\Eloquent\Collection;

interface DeductionRepositoryInterface
{
    public function getByWorkerAndDate(int $workerId, string $date): Collection;
    
    public function getByCompanyAndPeriod(int $companyId, string $from, string $to): Collection;
    
    public function getByWorkerAndPeriod(int $workerId, string $from, string $to): Collection;
    
    public function create(array $data): Deduction;
    
    public function delete(int $id): void;
    
    public function findById(int $id): ?Deduction;
}
