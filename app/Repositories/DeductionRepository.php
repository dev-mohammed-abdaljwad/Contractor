<?php

namespace App\Repositories;

use App\Models\Deduction;
use App\Models\DailyDistribution;
use App\Repositories\Interfaces\DeductionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DeductionRepository implements DeductionRepositoryInterface
{
    /**
     * Create a new deduction.
     */
    public function create(array $data): Deduction
    {
        return Deduction::create($data);
    }

    /**
     * Find a deduction by ID.
     */
    public function findById(int $id): ?Deduction
    {
        return Deduction::withRelations()->find($id);
    }

    /**
     * Find all deductions for a worker with optional filters.
     */
    public function findByWorker(int $workerId, array $filters = []): Collection
    {
        $query = Deduction::forWorker($workerId)->withRelations();

        // Apply filter: week / month / custom date range
        if (!empty($filters['filter'])) {
            match ($filters['filter']) {
                'week' => $query->thisWeek(),
                'month' => $query->thisMonth(),
                'range' => $this->applyDateRange($query, $filters),
                default => null,
            };
        }

        // Include or exclude reversals
        if ($filters['exclude_reversals'] ?? false) {
            $query->active();
        }

        return $query->orderByDesc('created_at')->get();
    }

    /**
     * Reverse a deduction.
     */
    public function reverse(Deduction $deduction, array $data): Deduction
    {
        $deduction->update([
            'is_reversed' => true,
            'reversed_at' => now(),
            'reversed_by' => $data['reversed_by'] ?? null,
            'reversal_reason' => $data['reversal_reason'] ?? null,
        ]);

        return $deduction->refresh()->load('reversedBy');
    }

    /**
     * Get total deductions for a worker in a specific month.
     */
    public function monthlyTotalForWorker(int $workerId, int $month, int $year): float
    {
        return (float) Deduction::forWorker($workerId)
            ->active()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('amount');
    }

    /**
     * Check if a worker has a distribution on a given date.
     */
    public function workerHasDistributionOnDate(int $workerId, Carbon $date): ?DailyDistribution
    {
        return DailyDistribution::whereHas('workers', 
            fn($q) => $q->where('worker_id', $workerId)
        )
        ->whereDate('distribution_date', $date->toDateString())
        ->with('company')
        ->first();
    }

    /**
     * Get deductions for a worker within a date period.
     */
    public function getByWorkerAndPeriod(int $workerId, string $from, string $to): Collection
    {
        return Deduction::forWorker($workerId)
            ->active()
            ->whereBetween('created_at', [$from, $to])
            ->select(['id', 'worker_id', 'distribution_id', 'type', 'amount', 'created_at'])
            ->with(['worker:id,name', 'distribution:id,company_id,distribution_date'])
            ->get();
    }

    /**
     * Apply custom date range filter to query.
     */
    private function applyDateRange($query, array $filters)
    {
        if (!empty($filters['from']) && !empty($filters['to'])) {
            $from = Carbon::parse($filters['from']);
            $to = Carbon::parse($filters['to']);
            return $query->dateRange($from, $to);
        }

        return $query;
    }
}
