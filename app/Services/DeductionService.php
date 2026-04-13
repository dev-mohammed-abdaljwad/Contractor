<?php

namespace App\Services;

use App\Exceptions\DeductionException;
use App\Models\Deduction;
use App\Repositories\Interfaces\DeductionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DeductionService
{
    public function __construct(
        private DeductionRepositoryInterface $deductionRepository,
    ) {}

    /**
     * Record a deduction for a worker.
     *
     * @param  array  $data
     * @param  int  $contractorId
     * @return Deduction
     * @throws DeductionException
     */
    public function recordDeduction(array $data, int $contractorId): Deduction
    {
        return DB::transaction(function () use ($data, $contractorId) {
            $workerId = $data['worker_id'];
            $date = Carbon::parse($data['date']);
            $type = $data['type'];

            // Verify worker has distribution on that date
            $distribution = $this->deductionRepository->workerHasDistributionOnDate($workerId, $date);

            if (!$distribution) {
                throw DeductionException::workerNotDistributed();
            }

            // Calculate amount from daily_wage × multiplier or use custom amount
            if ($type === 'custom' && isset($data['amount'])) {
                $amount = (float) $data['amount'];
            } else {
                $typeMultipliers = [
                    'quarter' => 0.25,
                    'half' => 0.5,
                    'full' => 1.0,
                ];

                $multiplier = $typeMultipliers[$type] ?? 0;
                $amount = $distribution->company->daily_wage * $multiplier;
            }

            // Create deduction with created_at set to the deduction date
            $deduction = new Deduction([
                'worker_id' => $workerId,
                'distribution_id' => $distribution->id,
                'contractor_id' => $contractorId,
                'type' => $type,
                'amount' => $amount,
                'reason' => $data['reason'] ?? null,
            ]);
            $deduction->created_at = $date; // Set created_at to match the deduction date
            $deduction->updated_at = $date;
            $deduction->save();
            return $deduction;
        });
    }

    /**
     * Reverse a deduction.
     *
     * @param  int  $deductionId
     * @param  string|null  $reason
     * @param  int  $reversedBy
     * @return Deduction
     * @throws DeductionException
     */
    public function reverseDeduction(int $deductionId, ?string $reason, int $reversedBy): Deduction
    {
        return DB::transaction(function () use ($deductionId, $reason, $reversedBy) {
            $deduction = $this->deductionRepository->findById($deductionId);

            if (!$deduction) {
                throw DeductionException::notFound();
            }

            if ($deduction->is_reversed) {
                throw DeductionException::alreadyReversed();
            }

            return $this->deductionRepository->reverse($deduction, [
                'reversed_by' => $reversedBy,
                'reversal_reason' => $reason,
            ]);
        });
    }

    /**
     * Get worker deduction history with filters and totals.
     *
     * @param  int  $workerId
     * @param  array  $filters
     * @return array
     */
    public function getWorkerDeductionHistory(int $workerId, array $filters = []): array
    {
        $deductions = $this->deductionRepository->findByWorker($workerId, $filters);

        $monthlyTotal = $this->deductionRepository->monthlyTotalForWorker(
            $workerId,
            now()->month,
            now()->year
        );

        $reversalCount = $deductions->where('is_reversed', true)->count();

        return [
            'deductions' => $deductions,
            'monthly_total' => $monthlyTotal,
            'reversal_count' => $reversalCount,
        ];
    }
}
