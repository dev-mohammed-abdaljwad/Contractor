<?php

namespace App\Services;

use App\Models\Collection;
use App\Repositories\Interfaces\CollectionRepositoryInterface;
use App\Repositories\Interfaces\DistributionRepositoryInterface;
use App\Repositories\Interfaces\DeductionRepositoryInterface;

class CollectionService
{
    public function __construct(
        private CollectionRepositoryInterface $collectionRepository,
        private DistributionRepositoryInterface $distributionRepository,
        private DeductionRepositoryInterface $deductionRepository,
    ) {}

    /**
     * Generate a collection statement preview (before saving)
     */
    public function generateStatement(int $companyId, string $from, string $to): array
    {
        $distributions = $this->distributionRepository->getByCompanyAndPeriod($companyId, $from, $to);
        $deductions = $this->deductionRepository->getByCompanyAndPeriod($companyId, $from, $to);

        $totalDays = $distributions->count();
        $totalWages = $distributions->sum('daily_wage_snapshot');
        $totalDeductions = $deductions->sum('amount');
        $netAmount = $totalWages - $totalDeductions;

        return [
            'company_id' => $companyId,
            'period_start' => $from,
            'period_end' => $to,
            'total_days_worked' => $totalDays,
            'total_wages' => (float) $totalWages,
            'total_deductions' => (float) $totalDeductions,
            'net_amount' => (float) $netAmount,
            'distribution_details' => $distributions->map(fn($d) => [
                'date' => $d->distribution_date,
                'worker_name' => $d->worker->name,
                'wage' => $d->daily_wage_snapshot,
            ])->toArray(),
            'deduction_details' => $deductions->map(fn($d) => [
                'date' => $d->deduction_date,
                'worker_name' => $d->worker->name,
                'type' => $d->type,
                'amount' => $d->amount,
                'reason' => $d->reason,
            ])->toArray(),
        ];
    }

    public function saveCollection(array $data): Collection
    {
        return $this->collectionRepository->create($data);
    }

    public function recordPayment(int $collectionId, string $method, string $date): void
    {
        $this->collectionRepository->update($collectionId, [
            'payment_method' => $method,
            'payment_date' => $date,
            'is_paid' => true,
        ]);
    }
}
