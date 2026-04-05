<?php

namespace App\Services;

use App\Exceptions\DuplicateDistributionException;
use App\Models\Company;
use App\Repositories\Interfaces\DistributionRepositoryInterface;
use Illuminate\Support\Collection;

class DistributionService
{
    public function __construct(
        private DistributionRepositoryInterface $distributionRepository,
    ) {}

    /**
     * Distribute workers to companies for a given date
     * @param int $contractorId
     * @param string $date (format: YYYY-MM-DD)
     * @param array $assignments [['company_id' => 1, 'worker_id' => 2], ...]
     * @throws DuplicateDistributionException
     */
    public function distributeWorkers(int $contractorId, string $date, array $assignments): void
    {
        foreach ($assignments as $assignment) {
            $workerId = $assignment['worker_id'];
            $companyId = $assignment['company_id'];

            // Check if worker already assigned on this date
            $existing = $this->distributionRepository->getByWorkerAndDate($workerId, $date);
            if ($existing) {
                throw new DuplicateDistributionException(
                    "Worker ID {$workerId} is already assigned on {$date}"
                );
            }

            // Get company and fetch daily wage snapshot
            $company = Company::find($companyId);
            
            $this->distributionRepository->create([
                'contractor_id' => $contractorId,
                'distribution_date' => $date,
                'company_id' => $companyId,
                'worker_id' => $workerId,
                'daily_wage_snapshot' => $company->daily_wage,
            ]);
        }
    }

    /**
     * Get daily summary grouped by company
     */
    public function getDailySummary(int $contractorId, string $date): array
    {
        $distributions = $this->distributionRepository->getByDateAndContractor($date, $contractorId);

        $summary = [];
        foreach ($distributions as $distribution) {
            $companyId = $distribution->company_id;
            
            if (!isset($summary[$companyId])) {
                $summary[$companyId] = [
                    'company_name' => $distribution->company->name,
                    'worker_count' => 0,
                    'total_wages' => 0,
                    'worker_names' => [],
                ];
            }

            $summary[$companyId]['worker_count']++;
            $summary[$companyId]['total_wages'] += $distribution->daily_wage_snapshot;
            $summary[$companyId]['worker_names'][] = $distribution->worker->name;
        }

        return $summary;
    }

    public function deleteDistribution(int $id): void
    {
        $this->distributionRepository->delete($id);
    }
}
