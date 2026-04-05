<?php

namespace App\Repositories;

use App\Models\DailyDistribution;
use App\Repositories\Interfaces\DistributionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DistributionRepository implements DistributionRepositoryInterface
{
    public function getByDateAndContractor(string $date, int $contractorId): Collection
    {
        return DailyDistribution::where('distribution_date', $date)
            ->where('contractor_id', $contractorId)
            ->get();
    }

    public function getByWorkerAndDate(int $workerId, string $date): ?DailyDistribution
    {
        return DailyDistribution::where('worker_id', $workerId)
            ->where('distribution_date', $date)
            ->first();
    }

    public function getByCompanyAndPeriod(int $companyId, string $from, string $to): Collection
    {
        return DailyDistribution::where('company_id', $companyId)
            ->whereBetween('distribution_date', [$from, $to])
            ->get();
    }

    public function getByWorkerAndPeriod(int $workerId, string $from, string $to): Collection
    {
        return DailyDistribution::where('worker_id', $workerId)
            ->whereBetween('distribution_date', [$from, $to])
            ->get();
    }

    public function create(array $data): DailyDistribution
    {
        return DailyDistribution::create($data);
    }

    public function delete(int $id): void
    {
        DailyDistribution::findOrFail($id)->delete();
    }
}
