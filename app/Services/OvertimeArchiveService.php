<?php

namespace App\Services;

use App\Models\OvertimeArchive;
use App\Models\Worker;
use App\Models\Payment;
use App\Models\DailyDistribution;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OvertimeArchiveService
{
    /**
     * Archive overtime hours for a worker's week when payment is recorded
     * This is called when a payment is created for a worker
     */
    public function archiveWeeklyOvertimes(Worker $worker, Payment $payment): ?OvertimeArchive
    {
        return DB::transaction(function () use ($worker, $payment) {
            // Get the week dates from payment or calculate from current week
            $weekStart = $payment->payment_date->startOfWeek(Carbon::SUNDAY);
            $weekEnd = $weekStart->copy()->addDays(6)->endOfDay();

            // Get all distributions with overtime for this week
            $distributions = DailyDistribution::where('worker_id', $worker->id)
                ->whereBetween('distribution_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->where('overtime_hours', '>', 0)
                ->get();

            // If no overtime found, return null
            if ($distributions->isEmpty()) {
                return null;
            }

            // Build daily records array
            $dailyRecords = [];
            $totalHours = 0;
            $totalAmount = 0;

            foreach ($distributions as $dist) {
                if ($dist->overtime_hours > 0) {
                    $amount = $dist->overtime_amount ?? 0;
                    
                    $dailyRecords[] = [
                        'date' => $dist->distribution_date->toDateString(),
                        'hours' => (float) $dist->overtime_hours,
                        'rate' => (float) $dist->overtime_rate,
                        'amount' => (float) $amount,
                    ];

                    $totalHours += $dist->overtime_hours;
                    $totalAmount += $amount;
                }
            }

            // Create archive record
            $archive = OvertimeArchive::create([
                'worker_id' => $worker->id,
                'payment_id' => $payment->id,
                'contractor_id' => $payment->contractor_id,
                'week_start' => $weekStart->toDateString(),
                'week_end' => $weekEnd->toDateString(),
                'total_overtime_hours' => $totalHours,
                'total_overtime_amount' => $totalAmount,
                'daily_records' => $dailyRecords,
            ]);

            // ZeroOut overtime hours in distributions for new week
            $distributions->each(function ($dist) {
                $dist->update([
                    'overtime_hours' => 0,
                    'overtime_rate' => 0,
                ]);
            });

            return $archive;
        });
    }

    /**
     * Get all overtime archives for a worker
     */
    public function getWorkerArchives(Worker $worker, int $limit = 12)
    {
        return OvertimeArchive::where('worker_id', $worker->id)
            ->orderBy('week_end', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get overtime archives for a specific payment
     */
    public function getPaymentArchive(Payment $payment): ?OvertimeArchive
    {
        return OvertimeArchive::where('payment_id', $payment->id)
            ->first();
    }

    /**
     * Get current week overtime summary for a worker
     */
    public function getCurrentWeekOvertime(Worker $worker): array
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $weekEnd = $weekStart->copy()->addDays(6)->endOfDay();

        $distributions = DailyDistribution::where('worker_id', $worker->id)
            ->whereBetween('distribution_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->where('overtime_hours', '>', 0)
            ->get();

        $totalHours = 0;
        $totalAmount = 0;

        foreach ($distributions as $dist) {
            $totalHours += $dist->overtime_hours;
            $totalAmount += $dist->overtime_amount ?? 0;
        }

        return [
            'total_hours' => $totalHours,
            'total_amount' => $totalAmount,
            'distributions_count' => $distributions->count(),
            'week_start' => $weekStart,
            'week_end' => $weekEnd,
        ];
    }
}
