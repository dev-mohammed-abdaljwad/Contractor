<?php

namespace App\Services;

use App\Exceptions\DuplicateDistributionException;
use App\Models\Company;
use App\Models\DailyDistribution;
use App\Models\DistributionActionLog;
use App\Repositories\Interfaces\DistributionRepositoryInterface;
use App\Repositories\Interfaces\DeductionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DistributionService
{
    public function __construct(
        private DistributionRepositoryInterface $distributionRepository,
        private DeductionRepositoryInterface $deductionRepository,
    ) {}

    /**
     * Distribute workers to companies for a given date
     * @param int $contractorId
     * @param string $date (format: YYYY-MM-DD)
     * @param array $assignments [['company_id' => 1, 'worker_ids' => [2, 3]], ...]
     * @throws DuplicateDistributionException
     */
    public function distributeWorkers(int $contractorId, string $date, array $assignments): void
    {
        // Group assignments by company to handle many-to-many
        $byCompany = collect($assignments)->groupBy('company_id');

        foreach ($byCompany as $companyId => $companyAssignments) {
            $company = Company::find($companyId);
            
            // Check if distribution already exists for this company on this date
            $distribution = DailyDistribution::where('contractor_id', $contractorId)
                ->where('distribution_date', $date)
                ->where('company_id', $companyId)
                ->first();

            // Collect all worker IDs for this company
            $workerIds = collect($companyAssignments)->pluck('worker_id')->unique()->toArray();

            // Check for duplicate assignments
            foreach ($workerIds as $workerId) {
                $existing = $this->distributionRepository->getByWorkerAndDate($workerId, $date);
                if ($existing) {
                    throw new DuplicateDistributionException(
                        "Worker ID {$workerId} is already assigned on {$date}"
                    );
                }
            }

            // Create or update distribution
            if (!$distribution) {
                $totalAmount = count($workerIds) * $company->daily_wage;
                $distribution = $this->distributionRepository->create([
                    'contractor_id' => $contractorId,
                    'distribution_date' => $date,
                    'company_id' => $companyId,
                    'total_amount' => $totalAmount,
                ]);
            }

            // Attach workers to distribution (many-to-many)
            $distribution->workers()->attach($workerIds);

            // Log the creation
            DistributionActionLog::create([
                'contractor_id' => $contractorId,
                'daily_distribution_id' => $distribution->id,
                'action' => 'created',
                'new_data' => [
                    'company_id' => $companyId,
                    'worker_count' => count($workerIds),
                    'total_amount' => count($workerIds) * $company->daily_wage,
                ],
            ]);
        }
    }

    /**
     * Get daily summary grouped by company
     */
    public function getDailySummary(int $contractorId, string $date): array
    {
        $distributions = $this->distributionRepository->getByDateAndContractor($date, $contractorId)->with('workers', 'company');

        $summary = [];
        foreach ($distributions as $distribution) {
            $companyId = $distribution->company_id;
            
            if (!isset($summary[$companyId])) {
                $summary[$companyId] = [
                    'company_id' => $companyId,
                    'company_name' => $distribution->company->name,
                    'daily_wage' => $distribution->company->daily_wage,
                    'worker_count' => 0,
                    'total_wages' => 0,
                    'total_deductions' => 0,
                    'net_total' => 0,
                    'workers' => [],
                ];
            }

            // Process each worker in the distribution
            foreach ($distribution->workers as $worker) {
                $workerEarnings = $distribution->company->daily_wage;
                // Get deductions for this worker on this date
                $deductions = $this->deductionRepository->getByWorkerAndDate($worker->id, $date);
                $deductionAmount = $deductions->sum('amount');

                $summary[$companyId]['worker_count']++;
                $summary[$companyId]['total_wages'] += $workerEarnings;
                $summary[$companyId]['total_deductions'] += $deductionAmount;
                $summary[$companyId]['net_total'] += ($workerEarnings - $deductionAmount);

                $summary[$companyId]['workers'][] = [
                    'id' => $worker->id,
                    'name' => $worker->name,
                    'distribution_id' => $distribution->id,
                    'wage' => $workerEarnings,
                    'deduction' => $deductionAmount,
                    'net' => $workerEarnings - $deductionAmount,
                ];
            }
        }

        return $summary;
    }

    /**
     * Calculate real-time earnings for a company based on selected workers
     * Used for US-11: Real-time earnings summary before confirming
     * 
     * @param int $companyId
     * @param array $workerIds - Array of worker IDs to calculate for
     * @param string $date - Distribution date
     * @return array
     */
    public function calculateRealTimeEarnings(int $companyId, array $workerIds, string $date): array
    {
        $company = Company::findOrFail($companyId);
        $dailyWage = $company->daily_wage;

        $totalWorkers = count($workerIds);
        $grossTotal = $totalWorkers * $dailyWage;
        $totalDeductions = 0;

        $workerDetails = [];
        
        foreach ($workerIds as $workerId) {
            // Get deductions for this worker on this date
            $deductions = $this->deductionRepository->getByWorkerAndDate($workerId, $date);
            $deductionAmount = $deductions->sum('amount');
            $totalDeductions += $deductionAmount;

            $workerDetails[] = [
                'worker_id' => $workerId,
                'gross' => $dailyWage,
                'deduction' => $deductionAmount,
                'net' => $dailyWage - $deductionAmount,
            ];
        }

        return [
            'company_id' => $companyId,
            'company_name' => $company->name,
            'daily_wage' => $dailyWage,
            'worker_count' => $totalWorkers,
            'gross_total' => $grossTotal,
            'total_deductions' => $totalDeductions,
            'net_total' => $grossTotal - $totalDeductions,
            'workers' => $workerDetails,
        ];
    }

    /**
     * Get already assigned workers for a specific date
     * Used for US-10: Mark workers already assigned to avoid duplicates
     */
    public function getAssignedWorkersForDate(int $contractorId, string $date): Collection
    {
        return DailyDistribution::where('contractor_id', $contractorId)
            ->where('distribution_date', $date)
            ->get();
    }

    /**
     * Edit a distribution (worker reassignment to different company)
     * Used for US-13: Edit or cancel a past distribution
     */
    public function editDistribution(int $distributionId, int $newCompanyId, int $newWorkerId, int $contractorId, ?string $reason = null): void
    {
        $distribution = DailyDistribution::findOrFail($distributionId);

        // Check if distribution is within 7 days
        if (!$this->canEditDistribution($distribution)) {
            throw new \Exception('Distribution cannot be edited after 7 days');
        }

        // Check if the newWorkerId is even in this distribution
        $workerInDistribution = $distribution->workers()->where('worker_id', $newWorkerId)->exists();
        if (!$workerInDistribution) {
            throw new \Exception('Worker is not assigned to this distribution');
        }

        // Store old data for logging
        $oldData = [
            'company_id' => $distribution->company_id,
            'worker_id' => $newWorkerId,
        ];

        // Get new company
        $newCompany = Company::findOrFail($newCompanyId);

        // Find or create distribution for new company on same date
        $newDistribution = DailyDistribution::where('contractor_id', $contractorId)
            ->where('distribution_date', $distribution->distribution_date)
            ->where('company_id', $newCompanyId)
            ->first();

        if (!$newDistribution) {
            $newDistribution = DailyDistribution::create([
                'contractor_id' => $contractorId,
                'distribution_date' => $distribution->distribution_date,
                'company_id' => $newCompanyId,
                'total_amount' => $newCompany->daily_wage,
            ]);
        }

        // Remove worker from old distribution
        $distribution->workers()->detach($newWorkerId);
        
        // Add worker to new distribution
        $newDistribution->workers()->attach($newWorkerId);

        // Update total amounts
        $distribution->total_amount = $distribution->workers()->count() * $distribution->company->daily_wage;
        $distribution->save();
        
        $newDistribution->total_amount = $newDistribution->workers()->count() * $newDistribution->company->daily_wage;
        $newDistribution->save();

        // Log the edit
        DistributionActionLog::create([
            'contractor_id' => $contractorId,
            'daily_distribution_id' => $distributionId,
            'action' => 'edited',
            'reason' => $reason,
            'old_data' => $oldData,
            'new_data' => [
                'company_id' => $newCompanyId,
                'worker_id' => $newWorkerId,
            ],
        ]);
    }

    /**
     * Cancel a distribution with logging
     * Used for US-13: Edit or cancel a past distribution
     */
    public function cancelDistribution(int $distributionId, int $contractorId, ?string $reason = null): void
    {
        $distribution = DailyDistribution::findOrFail($distributionId);

        // Check if distribution is within 7 days
        if (!$this->canEditDistribution($distribution)) {
            throw new \Exception('Distribution cannot be cancelled after 7 days');
        }

        // Store distribution data before deletion
        $workerCount = $distribution->workers()->count();
        $oldData = [
            'company_id' => $distribution->company_id,
            'worker_count' => $workerCount,
            'total_amount' => $distribution->total_amount,
        ];

        // Soft delete the distribution
        $distribution->delete();

        // Log the cancellation
        DistributionActionLog::create([
            'contractor_id' => $contractorId,
            'daily_distribution_id' => $distributionId,
            'action' => 'cancelled',
            'reason' => $reason,
            'old_data' => $oldData,
        ]);
    }

    /**
     * Check if a distribution can be edited/cancelled (within 7 days)
     */
    public function canEditDistribution(DailyDistribution $distribution): bool
    {
        $sevenDaysAgo = now()->subDays(7);
        return $distribution->distribution_date >= $sevenDaysAgo->toDateString();
    }

    /**
     * Get distribution history with action logs
     */
    public function getDistributionHistory(int $distributionId): DailyDistribution
    {
        return DailyDistribution::with('actionLogs')
            ->findOrFail($distributionId);
    }

    /**
     * Recalculate all affected balances after cancellation
     * Used for US-13: Cancelling recalculates balances automatically
     */
    public function recalculateCompanyBalance(int $companyId, string $date): array
    {
        return $this->getDailySummary(auth()->id() ?? 1, $date);
    }

    public function deleteDistribution(int $id): void
    {
        $distribution = DailyDistribution::findOrFail($id);
        $distribution->delete();
    }
}
