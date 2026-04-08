<?php

namespace App\Services;

use App\Repositories\Interfaces\DeductionRepositoryInterface;
use App\Repositories\Interfaces\DistributionRepositoryInterface;
use App\Repositories\Interfaces\AdvanceRepositoryInterface;

class WageCalculationService
{
    public function __construct(
        private DistributionRepositoryInterface $distributionRepository,
        private DeductionRepositoryInterface $deductionRepository,
        private AdvanceRepositoryInterface $advanceRepository,
    ) {}

    /**
     * Get worker earnings ledger for a period
     */
    public function getWorkerLedger(int $workerId, string $from, string $to): array
    {
        $distributions = $this->distributionRepository->getByWorkerAndPeriod($workerId, $from, $to);
        $deductions = $this->deductionRepository->getByWorkerAndPeriod($workerId, $from, $to);
        $advances = $this->advanceRepository->findByWorker($workerId);

        // Calculate totals
        $grossWages = $distributions->sum(fn($dist) => $dist->company->daily_wage);
        $totalDeductions = $deductions->sum('amount');
        $totalAdvances = $advances->sum('amount');
        $netPayable = $grossWages - $totalDeductions - $totalAdvances;

        // Build daily breakdown
        $breakdown = [];
        foreach ($distributions as $dist) {
            $date = $dist->distribution_date->format('Y-m-d');
            if (!isset($breakdown[$date])) {
                $breakdown[$date] = [
                    'date' => $date,
                    'gross_wage' => 0,
                    'deductions' => 0,
                    'company_names' => [],
                ];
            }
            $breakdown[$date]['gross_wage'] += $dist->company->daily_wage;
            $breakdown[$date]['company_names'][] = $dist->company->name;
        }

        foreach ($deductions as $ded) {
            $date = $ded->created_at->format('Y-m-d');
            if (isset($breakdown[$date])) {
                $breakdown[$date]['deductions'] += $ded->amount;
            }
        }

        return [
            'gross_wages' => $grossWages,
            'total_deductions' => $totalDeductions,
            'total_advances' => $totalAdvances,
            'net_payable' => max(0, $netPayable),
            'breakdown' => array_values($breakdown),
        ];
    }

    /**
     * Get company's liability to contractor
     */
    public function getCompanyLiability(int $companyId, string $from, string $to): array
    {
        $distributions = $this->distributionRepository->getByCompanyAndPeriod($companyId, $from, $to);
        $deductions = $this->deductionRepository->getByCompanyAndPeriod($companyId, $from, $to);

        return [
            'total_days' => $distributions->count(),
            'total_wages' => $distributions->sum(fn($dist) => $dist->company->daily_wage),
            'total_deductions' => $deductions->sum('amount'),
            'net_owed' => $distributions->sum(fn($dist) => $dist->company->daily_wage) - $deductions->sum('amount'),
        ];
    }
}
