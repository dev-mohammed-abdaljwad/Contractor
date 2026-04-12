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
        $distributions = $this->distributionRepository->getByCompanyAndPeriod($companyId, $from, $to)
            ->load('company', 'workers');
        $deductions = $this->deductionRepository->getByCompanyAndPeriod($companyId, $from, $to);

        // Calculate total workers across all distributions (each distribution can have multiple workers)
        $totalWorkerDays = $distributions->sum(fn($dist) => $dist->workers->count());
        $totalWages = $distributions->sum(fn($dist) => $dist->workers->count() * $dist->company->daily_wage);
        $totalDeductions = $deductions->sum('amount');
        $netAmount = $totalWages - $totalDeductions;

        // Flatten distribution-worker pairs
        $distributionDetails = [];
        foreach ($distributions as $dist) {
            foreach ($dist->workers as $worker) {
                $distributionDetails[] = [
                    'date' => $dist->distribution_date,
                    'worker_name' => $worker->name,
                    'wage' => $dist->company->daily_wage,
                ];
            }
        }

        return [
            'company_id' => $companyId,
            'period_start' => $from,
            'period_end' => $to,
            'total_days_worked' => $totalWorkerDays,
            'total_wages' => (float) $totalWages,
            'total_deductions' => (float) $totalDeductions,
            'net_amount' => (float) $netAmount,
            'distribution_details' => $distributionDetails,
            'deduction_details' => $deductions->map(fn($d) => [
                'date' => $d->created_at,
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
