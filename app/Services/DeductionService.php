<?php

namespace App\Services;

use App\Exceptions\InsufficientWageException;
use App\Models\Deduction;
use App\Repositories\Interfaces\DeductionRepositoryInterface;
use App\Repositories\Interfaces\DistributionRepositoryInterface;

class DeductionService
{
    public function __construct(
        private DeductionRepositoryInterface $deductionRepository,
        private DistributionRepositoryInterface $distributionRepository,
    ) {}

    /**
     * Calculate deduction amount based on type
     * @param int $workerId
     * @param string $date (format: YYYY-MM-DD)
     * @param string $type 'quarter', 'half', 'full', 'custom'
     * @param float|null $customAmount
     * @throws InsufficientWageException
     */
    public function calculateAmount(int $workerId, string $date, string $type, ?float $customAmount = null): float
    {
        // Get worker's wage snapshot for that date
        $distribution = $this->distributionRepository->getByWorkerAndDate($workerId, $date);
        
        if (!$distribution) {
            throw new InsufficientWageException('No distribution found for worker on this date');
        }

        $snapshot = $distribution->daily_wage_snapshot;

        return match($type) {
            'quarter' => $snapshot * 0.25,
            'half' => $snapshot * 0.5,
            'full' => $snapshot,
            'custom' => $customAmount ?? 0,
            default => 0,
        };
    }

    public function storeDeduction(array $data): Deduction
    {
        // Validate and calculate amount
        $amount = $this->calculateAmount(
            $data['worker_id'],
            $data['deduction_date'],
            $data['type'],
            $data['amount'] ?? null
        );

        $data['amount'] = $amount;

        return $this->deductionRepository->create($data);
    }

    public function deleteDeduction(int $id): void
    {
        $this->deductionRepository->delete($id);
    }
}
