<?php

namespace App\Repositories;

use App\Models\DailyDistribution;
use App\Repositories\Interfaces\DistributionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DistributionRepository implements DistributionRepositoryInterface
{
    /**
     * Get all distributions for a contractor — index page
     * 3 queries: distributions count + workers + companies
     */
    public function getAllByContractor(int $contractorId): Collection
    {
        return DailyDistribution::where('contractor_id', $contractorId)
            ->whereNull('deleted_at')
            ->select(['id', 'contractor_id', 'company_id', 'distribution_date', 'total_amount', 'created_at'])
            ->withCount('workers')
            ->with([
                'workers:id,name,phone',
                'company:id,name,daily_wage',
            ])
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get distributions for a date — with workers and company loaded
     */
    public function getByDateAndContractor(string $date, int $contractorId): Collection
    {
        return DailyDistribution::where('distribution_date', $date)
            ->where('contractor_id', $contractorId)
            ->select(['id', 'contractor_id', 'company_id', 'distribution_date', 'total_amount'])
            ->with([
                'workers:id,name,phone',
                'company:id,name,daily_wage',
            ])
            ->get();
    }

    /**
     * Get assigned worker IDs for a date — single optimized query
     */
    public function getAssignedWorkerIdsForDate(string $date, int $contractorId): array
    {
        return DailyDistribution::where('distribution_date', $date)
            ->where('contractor_id', $contractorId)
            ->with('workers:id')
            ->get()
            ->flatMap(fn($dist) => $dist->workers->pluck('id'))
            ->unique()
            ->toArray();
    }

    /**
     * Get distributions for date with deductions — for getDailySummary
     * Loads everything needed in 4 queries
     */
    public function getByDateWithDeductions(string $date, int $contractorId): Collection
    {
        return DailyDistribution::where('distribution_date', $date)
            ->where('contractor_id', $contractorId)
            ->select(['id', 'contractor_id', 'company_id', 'distribution_date', 'total_amount'])
            ->with([
                'workers:id,name,phone',
                'company:id,name,daily_wage',
                // Load deductions scoped to this date only
                'workers.deductions' => fn($q) => $q
                    ->whereDate('created_at', $date)
                    ->select(['id', 'worker_id', 'amount', 'type']),
            ])
            ->get();
    }

    public function getByWorkerAndDate(int $workerId, string $date): ?DailyDistribution
    {
        return DailyDistribution::whereHas('workers', fn($q) => $q->where('worker_id', $workerId))
            ->where('distribution_date', $date)
            ->select(['id', 'contractor_id', 'company_id', 'distribution_date'])
            ->first();
    }

    /**
     * Get assigned worker IDs from a list — batch check (no loop queries)
     */
    public function getAssignedWorkerIdsFromList(array $workerIds, string $date): array
    {
        return DailyDistribution::where('distribution_date', $date)
            ->whereHas('workers', fn($q) => $q->whereIn('worker_id', $workerIds))
            ->with(['workers' => fn($q) => $q->whereIn('worker_id', $workerIds)->select('id', 'name')])
            ->get()
            ->flatMap(fn($dist) => $dist->workers->pluck('id'))
            ->unique()
            ->toArray();
    }

    public function getByCompanyAndPeriod(int $companyId, string $from, string $to): Collection
    {
        $fromDate = Carbon::parse($from)->format('Y-m-d');
        $toDate = Carbon::parse($to)->format('Y-m-d');
        
        return DailyDistribution::where('company_id', $companyId)
            ->whereBetween('distribution_date', [$fromDate, $toDate])
            ->select(['id', 'company_id', 'contractor_id', 'distribution_date', 'total_amount'])
            ->with(['workers:id,name'])
            ->get();
    }

    public function getByWorkerAndPeriod(int $workerId, string $from, string $to): Collection
    {
        $fromDate = Carbon::parse($from)->format('Y-m-d');
        $toDate = Carbon::parse($to)->format('Y-m-d');
        
        return DailyDistribution::whereHas('workers', fn($q) => $q->where('worker_id', $workerId))
            ->whereBetween('distribution_date', [$fromDate, $toDate])
            ->select(['id', 'company_id', 'distribution_date', 'total_amount'])
            ->with(['company:id,name,daily_wage'])
            ->get();
    }

    public function findByIdWithDetails(int $id): DailyDistribution
    {
        return DailyDistribution::withCount('workers')
        ->with([
            'workers:id,name,phone',
            'company:id,name,daily_wage',
            'actionLogs',
        ])
        ->findOrFail($id);
    }

    public function create(array $data): DailyDistribution
    {
        return DailyDistribution::create($data);
    }

    public function delete(int $id): void
    {
        DailyDistribution::findOrFail($id)->delete();
    }
}
