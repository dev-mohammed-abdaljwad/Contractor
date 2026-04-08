<?php

namespace App\Repositories\Interfaces;

use App\Models\Deduction;
use App\Models\DailyDistribution;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface DeductionRepositoryInterface
{
    /**
     * Create a new deduction.
     *
     * @param  array  $data
     * @return Deduction
     */
    public function create(array $data): Deduction;

    /**
     * Find a deduction by ID.
     *
     * @param  int  $id
     * @return Deduction|null
     */
    public function findById(int $id): ?Deduction;

    /**
     * Find all deductions for a worker with optional filters.
     *
     * @param  int  $workerId
     * @param  array  $filters
     * @return Collection
     */
    public function findByWorker(int $workerId, array $filters = []): Collection;

    /**
     * Reverse a deduction.
     *
     * @param  Deduction  $deduction
     * @param  array  $data
     * @return Deduction
     */
    public function reverse(Deduction $deduction, array $data): Deduction;

    /**
     * Get total deductions for a worker in a specific month.
     *
     * @param  int  $workerId
     * @param  int  $month
     * @param  int  $year
     * @return float
     */
    public function monthlyTotalForWorker(int $workerId, int $month, int $year): float;

    /**
     * Check if a worker has a distribution on a given date.
     *
     * @param  int  $workerId
     * @param  Carbon  $date
     * @return DailyDistribution|null
     */
    public function workerHasDistributionOnDate(int $workerId, Carbon $date): ?DailyDistribution;

    /**
     * Get deductions for a worker within a date period.
     *
     * @param  int  $workerId
     * @param  string  $from
     * @param  string  $to
     * @return Collection
     */
    public function getByWorkerAndPeriod(int $workerId, string $from, string $to): Collection;
}
