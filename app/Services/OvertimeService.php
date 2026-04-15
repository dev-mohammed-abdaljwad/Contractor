<?php

namespace App\Services;

use App\Exceptions\OvertimeException;
use App\Models\DailyDistribution;
use App\Models\Worker;
use App\Repositories\Interfaces\OvertimeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class OvertimeService
{
    private OvertimeRepositoryInterface $overtimeRepository;

    public function __construct(OvertimeRepositoryInterface $overtimeRepository)
    {
        $this->overtimeRepository = $overtimeRepository;
    }

    /**
     * Record overtime hours for a single distribution
     *
     * @param int $distributionId
     * @param float $hours
     * @param int $contractorId
     * @return DailyDistribution
     * @throws OvertimeException
     */
    public function recordOvertime(int $distributionId, float $hours, int $contractorId): DailyDistribution
    {
        return DB::transaction(function () use ($distributionId, $hours, $contractorId) {
            // Find the distribution
            $distribution = DailyDistribution::find($distributionId);
            if (!$distribution) {
                throw OvertimeException::distributionNotFound();
            }

            // Verify authorization
            if ($distribution->contractor_id !== $contractorId) {
                throw OvertimeException::unauthorized();
            }

            // Check if distribution is within 7 days
            if (!$distribution->canEdit()) {
                throw OvertimeException::tooOld();
            }

            // Verify worker was distributed on this day
            if ($distribution->workers->count() === 0) {
                throw OvertimeException::workerNotDistributed();
            }

            // Validate hours
            if ($hours < 0 || $hours > 12) {
                throw OvertimeException::invalidHours();
            }

            // Get contractor's overtime hourly rate from preferences
            $user = auth()->user();
            $preferences = $user->preferences;
            $overtimeRate = (float) ($preferences?->overtime_hourly_rate ?? 20);

            // Update the distribution
            return $this->overtimeRepository->updateOvertime($distributionId, $hours, $overtimeRate);
        });
    }

    /**
     * Record overtime for multiple distributions at once
     *
     * @param array $entries Array of ['distribution_id' => x, 'overtime_hours' => y]
     * @param int $contractorId
     * @return array Updated distributions
     * @throws OvertimeException
     */
    public function recordBulkOvertime(array $entries, int $contractorId): array
    {
        return DB::transaction(function () use ($entries, $contractorId) {
            $results = [];

            foreach ($entries as $entry) {
                try {
                    $result = $this->recordOvertime(
                        $entry['distribution_id'],
                        $entry['overtime_hours'],
                        $contractorId
                    );
                    $results[] = $result;
                } catch (OvertimeException $e) {
                    // Log the error but continue processing other entries
                    logger()->warning('Overtime recording failed', [
                        'distribution_id' => $entry['distribution_id'],
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return $results;
        });
    }

    /**
     * Get weekly view data for a worker
     *
     * @param int $workerId
     * @param int $contractorId
     * @param ?Carbon $weekStart Optional week start date (default: current week start)
     * @return array
     * @throws OvertimeException
     */
    public function getWeeklyView(int $workerId, int $contractorId, ?Carbon $weekStart = null): array
    {
        // Verify worker belongs to contractor
        $worker = Worker::where('id', $workerId)
            ->where('contractor_id', $contractorId)
            ->firstOrFail();

        // Default to current week start (Sunday)
        if (!$weekStart) {
            $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        }

        $weekEnd = $weekStart->copy()->addDays(6)->endOfDay();

        $distributions = $this->overtimeRepository->getWeeklyDistributions($workerId, $weekStart, $weekEnd);
        $summary = $this->overtimeRepository->getWeeklySummary($workerId, $weekStart, $weekEnd);

        return [
            'worker' => $worker,
            'distributions' => $distributions,
            'summary' => $summary,
            'week_start' => $weekStart,
            'week_end' => $weekEnd,
        ];
    }

    /**
     * Get monthly overtime summary for a worker
     *
     * @param int $workerId
     * @param int $contractorId
     * @param int $month
     * @param int $year
     * @return Collection
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getMonthlyOvertimeSummary(int $workerId, int $contractorId, int $month, int $year): Collection
    {
        // Verify worker belongs to contractor
        Worker::where('id', $workerId)
            ->where('contractor_id', $contractorId)
            ->firstOrFail();

        return $this->overtimeRepository->getWorkerOvertimeByMonth($workerId, $month, $year);
    }

    /**
     * Record overtime for all workers in a company on a specific date
     *
     * @param int $companyId
     * @param string $distributionDate
     * @param float $overtimeHours
     * @param int $contractorId
     * @return array{updated: array, skipped: array}
     * @throws OvertimeException
     */
    public function bulkOvertimeByCompany(int $companyId, string $distributionDate, float $overtimeHours, int $contractorId): array
    {
        return DB::transaction(function () use ($companyId, $distributionDate, $overtimeHours, $contractorId) {
            // Get the date
            try {
                $date = Carbon::parse($distributionDate)->toDateString();
            } catch (\Exception $e) {
                throw OvertimeException::invalidDate();
            }

            // Verify company belongs to contractor
            $company = \App\Models\Company::where('id', $companyId)
                ->where('contractor_id', $contractorId)
                ->firstOrFail();

            // Get all distributions for this company on this date
            $distributions = DailyDistribution::where('company_id', $companyId)
                ->where('distribution_date', $date)
                ->where('contractor_id', $contractorId)
                ->get();

            if ($distributions->isEmpty()) {
                throw OvertimeException::noDistributionsFound();
            }

            // Get company's overtime hourly rate (with fallback to contractor preferences)
            $user = auth()->user() ?? \App\Models\User::find($contractorId);
            $preferences = $user->preferences;
            $overtimeRate = (float) ($company->overtime_rate ?? $preferences?->overtime_hourly_rate ?? 20);

            $updated = [];
            $skipped = [];

            foreach ($distributions as $distribution) {
                try {
                    // Check if distribution is within edit window
                    if (!$distribution->canEdit()) {
                        $skipped[] = [
                            'distribution_id' => $distribution->id,
                            'reason' => 'التوزيع قديم جداً (أكثر من 7 أيام)',
                        ];
                        continue;
                    }

                    // Update the distribution
                    $updated[] = $this->overtimeRepository->updateOvertime(
                        $distribution->id,
                        $overtimeHours,
                        $overtimeRate
                    );
                } catch (\Exception $e) {
                    logger()->warning('Bulk company overtime recording failed', [
                        'distribution_id' => $distribution->id,
                        'error' => $e->getMessage(),
                    ]);

                    $skipped[] = [
                        'distribution_id' => $distribution->id,
                        'reason' => 'حدث خطأ أثناء معالجة هذا التوزيع',
                    ];
                }
            }

            return [
                'updated' => collect($updated)->map(function ($dist) {
                    return [
                        'id' => $dist->id,
                        'overtime_hours' => $dist->overtime_hours,
                        'overtime_amount' => $dist->overtime_amount,
                    ];
                })->all(),
                'skipped' => $skipped,
            ];
        });
    }
}
