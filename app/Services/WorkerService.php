<?php

namespace App\Services;

use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class WorkerService
{

    /**
     * Enhance all workers using loaded relations — zero extra queries
     */
    public function enhanceWorkersCollection(
        Collection $workers,
        Carbon $today,
        Carbon $monthStart,
        Carbon $monthEnd
    ): Collection {
        $daysInMonthSoFar = $monthEnd->diffInDays($monthStart) + 1;

        return $workers->map(fn($worker) =>
            $this->enhanceWorker($worker, $today, $monthStart, $monthEnd, $daysInMonthSoFar)
        );
    }

    /**
     * Enhance single worker using already-loaded relations — zero queries
     */
    public function enhanceWorker(
        Worker $worker,
        Carbon $today,
        Carbon $monthStart,
        Carbon $monthEnd,
        int $daysInMonthSoFar
    ): Worker {
        $todayStr      = $today->toDateString();
        $monthStartStr = $monthStart->toDateString();
        $monthEndStr   = $monthEnd->toDateString();

        $distributions = $worker->distributions;
        $advances      = $worker->advances;
        $deductions    = $worker->deductions;

        // Today's distribution — filter on collection (no query)
        $todayDist = $distributions->first(
            fn($d) => Carbon::parse($d->distribution_date)->toDateString() === $todayStr
        );

        $worker->assigned_today   = $todayDist;
        $worker->distribution_today = (bool) $todayDist;
        $worker->assigned_company = $todayDist?->company?->name;
        $worker->company_today    = $todayDist?->company?->name;
        $worker->daily_wage       = $todayDist?->company?->daily_wage ?? 0;

        // Days worked this month — filter on collection (no query)
        $monthDists = $distributions->filter(
            fn($d) => Carbon::parse($d->distribution_date)->toDateString() >= $monthStartStr
                   && Carbon::parse($d->distribution_date)->toDateString() <= $monthEndStr
        );

        $daysWorked = $monthDists->pluck('distribution_date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->unique()
            ->count();

        $worker->days_worked      = $daysWorked;
        $worker->attendance_rate  = $daysInMonthSoFar > 0
            ? round(($daysWorked / $daysInMonthSoFar) * 100)
            : 0;

        // Last worked date — filter on collection (no query)
        $lastWork = $distributions->sortByDesc('distribution_date')->first();
        $worker->last_worked_date = $lastWork
            ? Carbon::parse($lastWork->distribution_date)->format('d/m/Y')
            : 'لم يعمل بعد';

        // Pending advances — filter on collection (no query)
        $pendingAdvances               = $advances->where('is_fully_collected', false);
        $worker->has_pending_advance   = $pendingAdvances->isNotEmpty();
        $worker->pending_advance_amount = $pendingAdvances->sum('amount');

        // Today's deductions — filter on collection (no query)
        $todayDeductions        = $deductions->filter(
            fn($d) => Carbon::parse($d->created_at)->toDateString() === $todayStr
        );
        $worker->has_deduction  = $todayDeductions->isNotEmpty();
        $worker->deduction_amount = $todayDeductions->sum('amount');

        return $worker;
    }

    /**
     * Build attendance calendar from loaded relations — zero queries
     */
    public function buildAttendanceCalendar(Worker $worker): array
    {
        $today      = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd   = $today->copy()->endOfMonth();

        // Key by date string — works on loaded collection
        $distributions = $worker->distributions
            ->keyBy(fn($d) => Carbon::parse($d->distribution_date)->toDateString());

        $deductions = $worker->deductions
            ->keyBy(fn($d) => Carbon::parse($d->created_at)->toDateString());

        $days        = [];
        $fullDays    = 0;
        $partialDays = 0;
        $absentDays  = 0;

        for ($day = 1; $day <= $monthEnd->day; $day++) {
            $date    = $monthStart->copy()->addDays($day - 1);
            $dateStr = $date->toDateString();

            $hasDist      = isset($distributions[$dateStr]);
            $hasDeduction = isset($deductions[$dateStr]);

            $status = match(true) {
                $hasDist && !$hasDeduction => 'full',
                $hasDist && $hasDeduction  => 'partial',
                default                    => 'absent',
            };

            match($status) {
                'full'    => $fullDays++,
                'partial' => $partialDays++,
                default   => $absentDays++,
            };

            $days[] = [
                'day'          => $day,
                'date'         => $dateStr,
                'status'       => $status,
                'isToday'      => $dateStr === $today->toDateString(),
                'distribution' => $distributions[$dateStr] ?? null,
                'deduction'    => $deductions[$dateStr] ?? null,
            ];
        }

        $daysWorked     = $fullDays + $partialDays;
        $attendanceRate = $monthEnd->day > 0
            ? round(($daysWorked / $monthEnd->day) * 100)
            : 0;

        return [
            'month'     => $monthStart->format('Y-m'),
            'monthName' => $monthStart->locale('ar')->isoFormat('MMMM YYYY'),
            'days'      => $days,
            'summary'   => [
                'fullDays'       => $fullDays,
                'partialDays'    => $partialDays,
                'absentDays'     => $absentDays,
                'attendanceRate' => $attendanceRate,
            ],
        ];
    }

    /**
     * Build show page data from loaded relations — zero queries
     */
    public function buildShowPageData(Worker $worker): array
    {
        $today         = Carbon::today();
        $monthStart    = $today->copy()->startOfMonth();
        $thirtyDaysAgo = $today->copy()->subDays(30);
        $weekStart     = $today->copy()->subDays(6);

        $distributions = $worker->distributions;
        $deductions    = $worker->deductions;
        $advances      = $worker->advances;

        // Frequent companies this month — no query
        $frequentCompanies = $distributions
            ->filter(fn($d) => Carbon::parse($d->distribution_date)->gte($monthStart))
            ->groupBy('company_id')
            ->map(fn($dists) => [
                'name' => $dists->first()->company?->name ?? 'شركة غير معروفة',
                'days' => $dists->count(),
            ])
            ->sortByDesc('days')
            ->take(5)
            ->values();

        $maxDays = $frequentCompanies->max('days') ?: 1;
        $frequentCompanies = $frequentCompanies->map(fn($c) => [
            ...$c,
            'percentage' => round(($c['days'] / $maxDays) * 100),
        ])->values();

        // This week activity — no query
        $deductionsByDate = $deductions->keyBy(
            fn($d) => Carbon::parse($d->deduction_date)->toDateString()
        );

        $thisWeekActivity = $distributions
            ->filter(fn($d) => Carbon::parse($d->distribution_date)->gte($weekStart))
            ->sortByDesc('distribution_date')
            ->map(function ($dist) use ($deductionsByDate) {
                $dateStr = Carbon::parse($dist->distribution_date)->toDateString();
                $date    = Carbon::parse($dist->distribution_date);

                return [
                    'day'          => $date->day,
                    'day_name'     => $date->locale('ar')->dayName,
                    'company_name' => $dist->company?->name ?? 'غير محدد',
                    'rate_label'   => number_format($dist->company?->daily_wage ?? 0) . ' ج/يوم',
                    'amount'       => number_format($dist->company?->daily_wage ?? 0, 0),
                    'status'       => isset($deductionsByDate[$dateStr]) ? 'partial' : 'full',
                ];
            })->values();

        // Deductions timeline — no query
        $deductionsTimeline = $deductions
            ->sortByDesc('created_at')
            ->map(fn($ded) => [
                'title'           => $this->deductionTypeLabel($ded->type),
                'date'            => Carbon::parse($ded->created_at)->format('d/m/Y'),
                'company_name'    => $ded->distribution?->company?->name ?? '-',
                'amount'          => (int) $ded->amount,
                'reason'          => $ded->reason ?? '-',
                'type'            => $ded->type,
                'original_amount' => (int) $ded->amount,
            ])->values();

        // Advances — no query
        $pendingAdvances = $advances
            ->where('is_fully_collected', false)
            ->sortByDesc('created_at')
            ->map(fn($adv) => [
                'amount' => (int) $adv->amount,
                'date'   => Carbon::parse($adv->date ?? $adv->created_at)->format('d M Y'),
                'recovery_method' => 'خصم من أول دفعة · لم يُحصَّل',
            ])->values();

        $collectedAdvances = $advances
            ->where('is_fully_collected', true)
            ->sortByDesc('updated_at')
            ->map(fn($adv) => [
                'amount'         => (int) $adv->amount,
                'date'           => Carbon::parse($adv->date ?? $adv->created_at)->format('d M Y'),
                'collected_date' => Carbon::parse($adv->updated_at)->format('d M Y'),
            ])->values();

        return [
            'frequentCompanies'  => $frequentCompanies,
            'thisWeekActivity'   => $thisWeekActivity,
            'deductionsTimeline' => $deductionsTimeline,
            'pendingAdvances'    => $pendingAdvances,
            'collectedAdvances'  => $collectedAdvances,
        ];
    }

    private function deductionTypeLabel(string $type): string
    {
        return match($type) {
            'full'     => 'خصم يوم كامل',
            'half'     => 'خصم نصف يوم',
            'quarter'  => 'خصم ربع يوم',
            'reversal' => 'إلغاء خصم',
            default    => 'خصم',
        };
    }

}
