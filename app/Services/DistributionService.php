<?php

namespace App\Services;

use App\Exceptions\DuplicateDistributionException;
use App\Models\Company;
use App\Models\DailyDistribution;
use App\Models\DistributionActionLog;
use App\Models\Worker;
use App\Repositories\Interfaces\DistributionRepositoryInterface;

class DistributionService
{
    public function __construct(
        private DistributionRepositoryInterface $distributionRepository,
    ) {}

    /**
     * Distribute workers — batch duplicate check, no loop queries.
     *
     * @param float|null $workerDailyWage  Optional override wage; if null, uses average of workers' default_daily_wage.
     */
    public function distributeWorkers(
        int $contractorId,
        int $companyId,
        array $workerIds,
        string $date,
        ?float $workerDailyWage = null
    ): DailyDistribution {
        // Single query to check all duplicates at once
        $alreadyAssigned = $this->distributionRepository
            ->getAssignedWorkerIdsFromList($workerIds, $date);

        if (!empty($alreadyAssigned)) {
            throw new DuplicateDistributionException($alreadyAssigned[0]);
        }

        $company     = Company::select(['id', 'name', 'daily_wage'])->findOrFail($companyId);
        $totalAmount = count($workerIds) * $company->daily_wage;

        // Snapshot worker_daily_wage at creation time.
        // If an explicit override is passed, use it; otherwise use the average of selected workers.
        if ($workerDailyWage === null) {
            $workerDailyWage = (float) Worker::whereIn('id', $workerIds)
                ->where('contractor_id', $contractorId)
                ->avg('default_daily_wage') ?? 0.0;
        }

        $distribution = $this->distributionRepository->create([
            'contractor_id'     => $contractorId,
            'company_id'        => $companyId,
            'distribution_date' => $date,
            'total_amount'      => $totalAmount,
            'worker_daily_wage' => $workerDailyWage,
        ]);

        $distribution->workers()->attach($workerIds);

        DistributionActionLog::create([
            'contractor_id'          => $contractorId,
            'daily_distribution_id'  => $distribution->id,
            'action'                 => 'created',
            'new_data'               => [
                'company_id'        => $companyId,
                'worker_count'      => count($workerIds),
                'total_amount'      => $totalAmount,
                'worker_daily_wage' => $workerDailyWage,
            ],
        ]);

        return $distribution;
    }

    /**
     * Get daily summary — uses preloaded deductions, no loop queries
     */
    public function getDailySummary(int $contractorId, string $date): array
    {
        // Single method loads everything needed
        $distributions = $this->distributionRepository->getByDateWithDeductions($date, $contractorId);

        $summary = [];

        foreach ($distributions as $distribution) {
            $companyId = $distribution->company_id;

            if (!isset($summary[$companyId])) {
                $summary[$companyId] = [
                    'company_id'       => $companyId,
                    'company_name'     => $distribution->company->name,
                    'daily_wage'       => $distribution->company->daily_wage,
                    'worker_count'     => 0,
                    'total_wages'      => 0,
                    'total_deductions' => 0,
                    'net_total'        => 0,
                    'workers'          => [],
                ];
            }

            foreach ($distribution->workers as $worker) {
                // ✅ deductions already loaded — zero queries
                $deductionAmount = $worker->deductions->sum('amount');
                $wage            = $distribution->company->daily_wage;

                $summary[$companyId]['worker_count']++;
                $summary[$companyId]['total_wages']      += $wage;
                $summary[$companyId]['total_deductions'] += $deductionAmount;
                $summary[$companyId]['net_total']        += ($wage - $deductionAmount);

                $summary[$companyId]['workers'][] = [
                    'id'              => $worker->id,
                    'name'            => $worker->name,
                    'distribution_id' => $distribution->id,
                    'wage'            => $wage,
                    'deduction'       => $deductionAmount,
                    'net'             => $wage - $deductionAmount,
                ];
            }
        }

        return $summary;
    }

    /**
     * Real-time earnings — batch load deductions, no loop queries
     */
    public function calculateRealTimeEarnings(int $companyId, array $workerIds, string $date): array
    {
        $company  = Company::select(['id', 'name', 'daily_wage'])->findOrFail($companyId);
        $dailyWage = $company->daily_wage;

        // Load all deductions for all workers in one query
        $deductionsByWorker = \App\Models\Deduction::whereIn('worker_id', $workerIds)
            ->whereDate('created_at', $date)
            ->select(['worker_id', 'amount'])
            ->get()
            ->groupBy('worker_id')
            ->map(fn($deds) => $deds->sum('amount'));

        $totalDeductions = 0;
        $workerDetails   = [];

        foreach ($workerIds as $workerId) {
            $deductionAmount  = $deductionsByWorker[$workerId] ?? 0;
            $totalDeductions += $deductionAmount;

            $workerDetails[] = [
                'worker_id' => $workerId,
                'gross'     => $dailyWage,
                'deduction' => $deductionAmount,
                'net'       => $dailyWage - $deductionAmount,
            ];
        }

        $grossTotal = count($workerIds) * $dailyWage;

        return [
            'company_id'       => $companyId,
            'company_name'     => $company->name,
            'daily_wage'       => $dailyWage,
            'worker_count'     => count($workerIds),
            'gross_total'      => $grossTotal,
            'total_deductions' => $totalDeductions,
            'net_total'        => $grossTotal - $totalDeductions,
            'workers'          => $workerDetails,
        ];
    }

    /**
     * Update distribution workers
     */
    public function updateDistribution(DailyDistribution $distribution, array $newWorkerIds): DailyDistribution
    {
        $oldWorkerIds = $distribution->workers->pluck('id')->toArray();

        $distribution->workers()->sync($newWorkerIds);
        $distribution->update([
            'total_amount' => count($newWorkerIds) * $distribution->company->daily_wage,
        ]);

        DistributionActionLog::create([
            'contractor_id'         => $distribution->contractor_id,
            'daily_distribution_id' => $distribution->id,
            'action'                => 'updated',
            'old_data'              => ['worker_ids' => $oldWorkerIds],
            'new_data'              => ['worker_ids' => $newWorkerIds],
        ]);

        return $distribution->fresh(['workers', 'company']);
    }

    /**
     * Cancel distribution with logging
     */
    public function cancelDistribution(DailyDistribution $distribution, int $contractorId): void
    {
        DistributionActionLog::create([
            'contractor_id'         => $contractorId,
            'daily_distribution_id' => $distribution->id,
            'action'                => 'cancelled',
            'old_data'              => [
                'company_id'   => $distribution->company_id,
                'worker_count' => $distribution->workers->count(),
                'total_amount' => $distribution->total_amount,
            ],
            'new_data' => ['cancelled_at' => now()],
        ]);

        $distribution->delete();
    }

    public function getDistributionHistory(int $distributionId): DailyDistribution
    {
        return $this->distributionRepository->findByIdWithDetails($distributionId);
    }
}
