<?php

namespace App\Repositories;

use App\Models\DailyDistribution;
use App\Models\Worker;
use App\Repositories\Interfaces\OvertimeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class OvertimeRepository implements OvertimeRepositoryInterface
{
    /**
     * Get weekly distributions for a worker with related data
     */
    public function getWeeklyDistributions(int $workerId, Carbon $weekStart, Carbon $weekEnd): Collection
    {
        return DailyDistribution::whereHas('workers', function ($query) use ($workerId) {
            $query->where('worker_id', $workerId);
        })
            ->whereBetween('distribution_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->with([
                'company:id,name,daily_wage',
                'workers' => function ($q) use ($workerId) {
                    $q->where('worker_id', $workerId);
                }
            ])
            ->orderBy('distribution_date', 'desc')
            ->get();
    }

    /**
     * Update overtime hours for a distribution
     */
    public function updateOvertime(int $distributionId, float $hours, float $rate): DailyDistribution
    {
        $distribution = DailyDistribution::findOrFail($distributionId);
        
        $distribution->update([
            'overtime_hours' => $hours,
            'overtime_rate' => $rate,
        ]);

        return $distribution->fresh();
    }

    /**
     * Get weekly summary including calculations for a worker
     */
    public function getWeeklySummary(int $workerId, Carbon $weekStart, Carbon $weekEnd): array
    {
        $distributions = $this->getWeeklyDistributions($workerId, $weekStart, $weekEnd);

        $daysWorked = $distributions->count();
        $totalOvertimeHours = $distributions->sum('overtime_hours');
        
        // Calculate earnings
        $regularEarnings = 0;
        $overtimeEarnings = $distributions->sum(function ($dist) {
            return $dist->overtime_amount;
        });

        // For regular earnings, sum the daily wage amounts
        $regularEarnings = $distributions->sum(function ($dist) {
            return $dist->total_amount ?? 0;
        });

        // Get deductions and advances for the week
        $totalDeductions = 0;
        $pendingAdvances = 0;

        $worker = Worker::find($workerId);
        if ($worker) {
            $totalDeductions = $worker->deductions()
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->sum('amount');

            $pendingAdvances = $worker->advances()
                ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->sum('amount_pending');
        }

        $grandTotal = $regularEarnings + $overtimeEarnings - $totalDeductions - $pendingAdvances;

        return [
            'days_worked' => $daysWorked,
            'overtime_hours' => (float) $totalOvertimeHours,
            'regular_earnings' => (float) $regularEarnings,
            'overtime_earnings' => (float) $overtimeEarnings,
            'total_deductions' => (float) $totalDeductions,
            'pending_advances' => (float) $pendingAdvances,
            'grand_total' => (float) max(0, $grandTotal),
            'days' => $distributions,
        ];
    }

    /**
     * Get worker overtime records by month
     */
    public function getWorkerOvertimeByMonth(int $workerId, int $month, int $year): Collection
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        return DailyDistribution::whereHas('workers', function ($query) use ($workerId) {
            $query->where('worker_id', $workerId);
        })
            ->withOvertime()
            ->whereBetween('distribution_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->with('company:id,name')
            ->orderBy('distribution_date', 'desc')
            ->get();
    }

    /**
     * Get current week overtime count for a worker
     */
    public function getCurrentWeekOvertimeCount(int $workerId): array
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $weekEnd = $weekStart->copy()->addDays(6)->endOfDay();

        $distributions = $this->getWeeklyDistributions($workerId, $weekStart, $weekEnd);
        
        $totalHours = $distributions->sum('overtime_hours');
        $totalAmount = $distributions->sum(function ($dist) {
            return $dist->overtime_amount ?? 0;
        });

        return [
            'total_hours' => (float) $totalHours,
            'total_amount' => (float) $totalAmount,
            'distribution_count' => $distributions->count(),
        ];
    }
}
