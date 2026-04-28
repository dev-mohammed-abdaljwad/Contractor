<?php

namespace App\Repositories\Interfaces;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

interface ProfitRepositoryInterface
{
    /**
     * Returns per-company profit summary for a given day.
     * Each item contains: company_id, company_name, workers_count,
     * total_revenue, total_worker_cost, total_deductions, overtime_cost, gross_profit.
     */
    public function getDailyProfitByCompany(string|Carbon $date, int $contractorId): Collection;

    /**
     * Returns per-company, per-day profit breakdown for the given week range.
     */
    public function getWeeklyProfitSummary(int $contractorId, Carbon $weekStart, Carbon $weekEnd): Collection;

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
    public function getMonthlyProfitSummary(int $contractorId, int $month, int $year): array;

    /**
     * Returns per-worker profit breakdown for all companies on a given day.
     * Returns a Support\Collection of plain objects (not Eloquent models).
     */
    public function getWorkerProfitBreakdown(int $contractorId, Carbon $date): Collection;

    /**
     * Returns companies ranked by gross profit this month, limited to $limit results.
     * Returns a Support\Collection of plain objects (not Eloquent models).
     */
    public function getTopProfitableCompanies(int $contractorId, int $limit = 5): Collection;
}
