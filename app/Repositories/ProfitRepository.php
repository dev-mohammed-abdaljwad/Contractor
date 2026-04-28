<?php

namespace App\Repositories;

use App\Models\DailyDistribution;
use App\Models\Deduction;
use App\Repositories\Interfaces\ProfitRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfitRepository implements ProfitRepositoryInterface
{
    /**
     * Returns per-company profit summary for a given day.
     *
     * For each company: revenue = company.daily_wage × workers_count,
     * workers_cost = SUM(distribution.worker_daily_wage) × workers_count,
     * deductions increase profit (worker gets less, company pays full),
     * overtime decreases profit.
     */
    public function getDailyProfitByCompany(string|Carbon $date, int $contractorId): Collection
    {
        $dateStr = ($date instanceof Carbon) ? $date->format('Y-m-d') : $date;

        // Load all distributions for this day with eager-loaded relations
        $distributions = DailyDistribution::where('contractor_id', $contractorId)
            ->where('distribution_date', $dateStr)
            ->whereNull('deleted_at')
            ->with([
                'company:id,name,daily_wage,contractor_rate',
                'workers',
                'workers.deductions' => fn($q) => $q
                    ->where('is_reversed', false)
                    ->select(['id', 'worker_id', 'distribution_id', 'amount']),
            ])
            ->get();

        // Group and aggregate by company
        return $distributions->map(function (DailyDistribution $dist) {
            $company      = $dist->company;
            $workersCount = $dist->workers->count();
            $companyWage  = (float) ($company->contractor_rate ?? $company->daily_wage ?? 0);
            $workerWage   = (float) ($dist->worker_daily_wage ?? 0);

            if ($workerWage === 0.0 && $workersCount > 0) {
                Log::warning("ProfitRepository: worker_daily_wage is 0 for distribution #{$dist->id}");
            }

            // Sum active deductions for all workers in this distribution
            $totalDeductions = $dist->workers->sum(function ($worker) use ($dist) {
                return $worker->deductions
                    ->where('distribution_id', $dist->id)
                    ->sum('amount');
            });

            $totalRevenue    = $companyWage * $workersCount;
            $totalWorkerCost = $workerWage * $workersCount;
            $overtimeCost    = (float) ($dist->overtime_hours ?? 0) * (float) ($dist->overtime_rate ?? 0);

            // gross_profit = revenue - (worker_cost - deductions) - overtime
            $grossProfit = $totalRevenue - ($totalWorkerCost - $totalDeductions) - $overtimeCost;

            return (object) [
                'distribution_id'   => $dist->id,
                'company_id'        => $company->id,
                'company_name'      => $company->name,
                'company_wage'      => $companyWage,
                'worker_wage'       => $workerWage,
                'workers_count'     => $workersCount,
                'total_revenue'     => $totalRevenue,
                'total_worker_cost' => $totalWorkerCost,
                'total_deductions'  => $totalDeductions,
                'overtime_cost'     => $overtimeCost,
                'gross_profit'      => $grossProfit,
            ];
        });
    }

    /**
     * Returns per-company, per-day profit breakdown for a given week range.
     */
    public function getWeeklyProfitSummary(int $contractorId, Carbon $weekStart, Carbon $weekEnd): Collection
    {
        $distributions = DailyDistribution::where('contractor_id', $contractorId)
            ->whereBetween('distribution_date', [
                $weekStart->format('Y-m-d'),
                $weekEnd->format('Y-m-d'),
            ])
            ->whereNull('deleted_at')
            ->with(['company:id,name,daily_wage,contractor_rate', 'workers'])
            ->get();

        return $distributions->map(function (DailyDistribution $dist) {
            $workersCount = $dist->workers->count();
            $companyWage  = (float) ($dist->company->contractor_rate ?? $dist->company->daily_wage ?? 0);
            $workerWage   = (float) ($dist->worker_daily_wage ?? 0);
            $overtimeCost = (float) ($dist->overtime_hours ?? 0) * (float) ($dist->overtime_rate ?? 0);

            // Fetch deductions for this distribution
            $totalDeductions = Deduction::where('distribution_id', $dist->id)
                ->where('is_reversed', false)
                ->sum('amount');

            $totalRevenue    = $companyWage * $workersCount;
            $totalWorkerCost = $workerWage * $workersCount;
            $grossProfit     = $totalRevenue - ($totalWorkerCost - $totalDeductions) - $overtimeCost;

            return (object) [
                'distribution_id'   => $dist->id,
                'date'              => $dist->distribution_date->format('Y-m-d'),
                'company_id'        => $dist->company_id,
                'company_name'      => $dist->company->name,
                'workers_count'     => $workersCount,
                'total_revenue'     => $totalRevenue,
                'total_worker_cost' => $totalWorkerCost,
                'total_deductions'  => $totalDeductions,
                'overtime_cost'     => $overtimeCost,
                'gross_profit'      => $grossProfit,
            ];
        });
    }

    /**
     * Returns monthly aggregate totals plus by_company and by_week sub-collections.
     *
     * @return array{
     *     total_revenue: float,
     *     total_workers_cost: float,
     *     total_deductions: float,
     *     total_overtime: float,
     *     gross_profit: float,
     *     profit_margin_pct: float,
     *     by_company: Collection,
     *     by_week: Collection,
     * }
     */
    public function getMonthlyProfitSummary(int $contractorId, int $month, int $year): array
    {
        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end   = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $weeklyData = $this->getWeeklyProfitSummary($contractorId, $start, $end);

        // Aggregate totals
        $totalRevenue    = (float) $weeklyData->sum('total_revenue');
        $totalWorkerCost = (float) $weeklyData->sum('total_worker_cost');
        $totalDeductions = (float) $weeklyData->sum('total_deductions');
        $totalOvertime   = (float) $weeklyData->sum('overtime_cost');
        $grossProfit     = $totalRevenue - ($totalWorkerCost - $totalDeductions) - $totalOvertime;
        $marginPct       = $totalRevenue > 0 ? round(($grossProfit / $totalRevenue) * 100, 1) : 0;

        // Group by company
        $byCompany = $weeklyData->groupBy('company_id')->map(function ($rows, $companyId) {
            $first = $rows->first();
            return (object) [
                'company_id'        => $companyId,
                'company_name'      => $first->company_name,
                'total_revenue'     => (float) $rows->sum('total_revenue'),
                'total_worker_cost' => (float) $rows->sum('total_worker_cost'),
                'total_deductions'  => (float) $rows->sum('total_deductions'),
                'overtime_cost'     => (float) $rows->sum('overtime_cost'),
                'gross_profit'      => (float) $rows->sum('gross_profit'),
                'days_worked'       => $rows->count(),
            ];
        })->values();

        // Group by ISO week number
        $byWeek = $weeklyData->groupBy(fn($row) => Carbon::parse($row->date)->weekOfYear)
            ->map(function ($rows, $weekNum) {
                $dates = $rows->pluck('date');
                return (object) [
                    'week_number'    => $weekNum,
                    'date_from'      => $dates->min(),
                    'date_to'        => $dates->max(),
                    'total_revenue'  => (float) $rows->sum('total_revenue'),
                    'gross_profit'   => (float) $rows->sum('gross_profit'),
                    'days'           => $rows->count(),
                ];
            })->values();

        return [
            'total_revenue'      => $totalRevenue,
            'total_workers_cost' => $totalWorkerCost,
            'total_deductions'   => $totalDeductions,
            'total_overtime'     => $totalOvertime,
            'gross_profit'       => $grossProfit,
            'profit_margin_pct'  => $marginPct,
            'by_company'         => $byCompany,
            'by_week'            => $byWeek,
        ];
    }

    /**
     * Returns per-worker profit breakdown for all companies on a given day.
     */
    public function getWorkerProfitBreakdown(int $contractorId, Carbon $date): Collection
    {
        $dateStr = $date->format('Y-m-d');

        $distributions = DailyDistribution::where('contractor_id', $contractorId)
            ->where('distribution_date', $dateStr)
            ->whereNull('deleted_at')
            ->with([
                'company:id,name,daily_wage,contractor_rate',
                'workers',
            ])
            ->get();

        $result = collect();

        foreach ($distributions as $dist) {
            $companyWage = (float) ($dist->company->contractor_rate ?? $dist->company->daily_wage ?? 0);
            $workerWage  = (float) ($dist->worker_daily_wage ?? 0);

            // Load deductions for this distribution in batch
            $deductionsByWorker = Deduction::where('distribution_id', $dist->id)
                ->where('is_reversed', false)
                ->select(['worker_id', 'amount'])
                ->get()
                ->groupBy('worker_id')
                ->map(fn($rows) => (float) $rows->sum('amount'));

            foreach ($dist->workers as $worker) {
                $deduction   = $deductionsByWorker[$worker->id] ?? 0.0;
                $netCost     = $workerWage - $deduction;
                $profit      = $companyWage - $netCost;

                $result->push((object) [
                    'worker_id'       => $worker->id,
                    'worker_name'     => $worker->name,
                    'company_id'      => $dist->company_id,
                    'company_name'    => $dist->company->name,
                    'company_wage'    => $companyWage,
                    'worker_wage'     => $workerWage,
                    'deduction'       => $deduction,
                    'net_worker_cost' => $netCost,
                    'profit'          => $profit,
                ]);
            }
        }

        return $result;
    }

    /**
     * Returns companies ranked by gross profit this month, limited to $limit results.
     */
    public function getTopProfitableCompanies(int $contractorId, int $limit = 5): Collection
    {
        $start = now()->startOfMonth()->format('Y-m-d');
        $end   = now()->endOfMonth()->format('Y-m-d');

        $distributions = DailyDistribution::where('contractor_id', $contractorId)
            ->whereBetween('distribution_date', [$start, $end])
            ->whereNull('deleted_at')
            ->with(['company:id,name,daily_wage,contractor_rate', 'workers'])
            ->get();

        return $distributions
            ->groupBy('company_id')
            ->map(function ($rows, $companyId) {
                $company = $rows->first()->company;

                $totalRevenue    = 0.0;
                $totalWorkerCost = 0.0;
                $totalDeductions = 0.0;
                $totalOvertime   = 0.0;

                foreach ($rows as $dist) {
                    $wc = $dist->workers->count();
                    $totalRevenue    += (float) ($company->contractor_rate ?? $company->daily_wage ?? 0) * $wc;
                    $totalWorkerCost += (float) ($dist->worker_daily_wage ?? 0) * $wc;
                    $totalOvertime   += (float) ($dist->overtime_hours ?? 0) * (float) ($dist->overtime_rate ?? 0);

                    $totalDeductions += (float) Deduction::where('distribution_id', $dist->id)
                        ->where('is_reversed', false)
                        ->sum('amount');
                }

                $grossProfit = $totalRevenue - ($totalWorkerCost - $totalDeductions) - $totalOvertime;

                return (object) [
                    'company_id'    => $companyId,
                    'company_name'  => $company->name,
                    'total_revenue' => $totalRevenue,
                    'gross_profit'  => $grossProfit,
                    'days_worked'   => $rows->count(),
                ];
            })
            ->sortByDesc('gross_profit')
            ->take($limit)
            ->values();
    }
}
