<?php

namespace App\Services;

use App\Models\DailyDistribution;
use App\Repositories\Interfaces\ProfitRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProfitService
{
    public function __construct(
        private ProfitRepositoryInterface $profitRepository,
    ) {}

    /**
     * Build the full daily profit report for a contractor.
     *
     * @return array{
     *     date: string,
     *     by_company: \Illuminate\Support\Collection,
     *     workers: \Illuminate\Support\Collection,
     *     totals: array{
     *         total_revenue: float,
     *         total_worker_cost: float,
     *         total_deductions: float,
     *         total_overtime: float,
     *         gross_profit: float,
     *         profit_margin_pct: float,
     *         workers_count: int,
     *         companies_count: int,
     *     },
     * }
     */
    public function getDailyReport(int $contractorId, Carbon $date): array
    {
        $byCompany = $this->profitRepository->getDailyProfitByCompany($date, $contractorId);
        $workers   = $this->profitRepository->getWorkerProfitBreakdown($contractorId, $date);

        $totalRevenue    = (float) $byCompany->sum('total_revenue');
        $totalWorkerCost = (float) $byCompany->sum('total_worker_cost');
        $totalDeductions = (float) $byCompany->sum('total_deductions');
        $totalOvertime   = (float) $byCompany->sum('overtime_cost');
        $grossProfit     = $totalRevenue - ($totalWorkerCost - $totalDeductions) - $totalOvertime;
        $marginPct       = $totalRevenue > 0 ? round(($grossProfit / $totalRevenue) * 100, 1) : 0;

        return [
            'date'       => $date->format('Y-m-d'),
            'by_company' => $byCompany,
            'workers'    => $workers,
            'totals'     => [
                'total_revenue'     => $totalRevenue,
                'total_worker_cost' => $totalWorkerCost,
                'total_deductions'  => $totalDeductions,
                'total_overtime'    => $totalOvertime,
                'gross_profit'      => $grossProfit,
                'profit_margin_pct' => $marginPct,
                'workers_count'     => $workers->count(),
                'companies_count'   => $byCompany->count(),
            ],
        ];
    }

    /**
     * Build the full monthly profit report for a contractor.
     *
     * @return array{
     *     month: int,
     *     year: int,
     *     summary: array,
     *     top_companies: \Illuminate\Support\Collection,
     * }
     */
    public function getMonthlyReport(int $contractorId, int $month, int $year): array
    {
        $summary      = $this->profitRepository->getMonthlyProfitSummary($contractorId, $month, $year);
        $topCompanies = $this->profitRepository->getTopProfitableCompanies($contractorId, 5);

        return [
            'month'         => $month,
            'year'          => $year,
            'summary'       => $summary,
            'top_companies' => $topCompanies,
        ];
    }

    /**
     * Calculate detailed profit breakdown for a single distribution.
     *
     * @return array{
     *     distribution_id: int,
     *     company_name: string,
     *     company_daily_wage: float,
     *     worker_daily_wage: float,
     *     workers_count: int,
     *     total_revenue: float,
     *     total_worker_cost: float,
     *     total_deductions: float,
     *     overtime_cost: float,
     *     gross_profit: float,
     *     profit_per_worker: float,
     * }
     */
    public function calculateDistributionProfit(int $distributionId): array
    {
        $dist = DailyDistribution::with([
            'company:id,name,daily_wage,contractor_rate',
            'workers',
        ])->findOrFail($distributionId);

        $workersCount    = $dist->workers->count();
        $companyWage     = (float) ($dist->company->daily_wage ?? 0);
        $workerWage      = (float) ($dist->worker_daily_wage ?? 0);
        $overtimeCost    = (float) ($dist->overtime_hours ?? 0) * (float) ($dist->overtime_rate ?? 0);

        if ($workerWage === 0.0) {
            Log::warning("ProfitService: worker_daily_wage is 0 for distribution #{$distributionId}");
        }

        $totalRevenue    = $companyWage * $workersCount;
        $totalWorkerCost = $workerWage * $workersCount;

        // Load deductions for this distribution
        $totalDeductions = (float) \App\Models\Deduction::where('distribution_id', $distributionId)
            ->where('is_reversed', false)
            ->sum('amount');

        $grossProfit    = $totalRevenue - ($totalWorkerCost - $totalDeductions) - $overtimeCost;
        $profitPerWorker = $workersCount > 0 ? round($grossProfit / $workersCount, 2) : 0;

        return [
            'distribution_id'    => $dist->id,
            'company_name'       => $dist->company->name,
            'company_daily_wage' => $companyWage,
            'worker_daily_wage'  => $workerWage,
            'workers_count'      => $workersCount,
            'total_revenue'      => $totalRevenue,
            'total_worker_cost'  => $totalWorkerCost,
            'total_deductions'   => $totalDeductions,
            'overtime_cost'      => $overtimeCost,
            'gross_profit'       => $grossProfit,
            'profit_per_worker'  => $profitPerWorker,
        ];
    }
}
