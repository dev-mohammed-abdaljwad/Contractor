<?php

namespace App\Repositories\Interfaces;

use App\Models\DailyDistribution;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface OvertimeRepositoryInterface
{
    /**
     * Get weekly distributions for a worker with related data
     *
     * @param int $workerId
     * @param Carbon $weekStart
     * @param Carbon $weekEnd
     * @return Collection
     */
    public function getWeeklyDistributions(int $workerId, Carbon $weekStart, Carbon $weekEnd): Collection;

    /**
     * Update overtime hours for a distribution
     *
     * @param int $distributionId
     * @param float $hours
     * @param float $rate
     * @return DailyDistribution
     */
    public function updateOvertime(int $distributionId, float $hours, float $rate): DailyDistribution;

    /**
     * Get weekly summary including calculations for a worker
     *
     * @param int $workerId
     * @param Carbon $weekStart
     * @param Carbon $weekEnd
     * @return array
     */
    public function getWeeklySummary(int $workerId, Carbon $weekStart, Carbon $weekEnd): array;

    /**
     * Get worker overtime records by month
     *
     * @param int $workerId
     * @param int $month
     * @param int $year
     * @return Collection
     */
    public function getWorkerOvertimeByMonth(int $workerId, int $month, int $year): Collection;

    /**
     * Get current week overtime count for a worker
     *
     * @param int $workerId
     * @return array
     */
    public function getCurrentWeekOvertimeCount(int $workerId): array;
}
