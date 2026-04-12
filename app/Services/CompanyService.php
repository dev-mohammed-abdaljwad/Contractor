<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\CompanyRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection as SupportCollection;

class CompanyService
{
    public function __construct(
        private CompanyRepository $companyRepository
    ) {}

    /**
     * Get all companies for contractor with enhanced statistics (no N+1)
     * Single optimized query loads all data needed
     */
    public function getEnhancedCompaniesForContractor(int $contractorId): array
    {
        // جيب كل الداتا في queries محدودة (4 queries فقط)
        $allCompanies = $this->companyRepository->getAllByContractorWithFullData($contractorId);

        $today        = Carbon::today();
        $monthStart   = Carbon::now()->startOfMonth();
        $monthEnd     = Carbon::now()->endOfMonth();

        // Map over already-loaded companies - no additional queries
        $enhancedCompanies = $allCompanies->map(
            fn($company) => $this->enhanceCompanyData($company, $today, $monthStart, $monthEnd)
        );

        $activeCompanies   = $enhancedCompanies->where('is_active', true)->values();
        $inactiveCompanies = $enhancedCompanies->where('is_active', false)->values();

        return [
            'activeCompanies'   => $activeCompanies,
            'inactiveCompanies' => $inactiveCompanies,
            'stats'             => $this->calculateGlobalStats($activeCompanies),
            'paymentCycles'     => $activeCompanies->pluck('payment_cycle')->unique()->values(),
            'overdueCompanies'  => $activeCompanies->where('payment_status', 'overdue')->values(),
        ];
    }

    /**
     * Enhance company with computed statistics using already-loaded data (no N+1)
     * Works on collections loaded by getAllByContractorWithFullData()
     */
    private function enhanceCompanyData(
        Company $company,
        Carbon $today,
        Carbon $monthStart,
        Carbon $monthEnd
    ): Company {
        // ✅ بنشتغل على الـ loaded collection — صفر queries إضافية
        $distributions = $company->distributions;
        $collections   = $company->collections;

        // اليوم - filter على collection (لا query)
        $todayDists = $distributions->filter(
            fn($d) => Carbon::parse($d->distribution_date)->toDateString() === $today->toDateString()
        );
        $company->workers_today = $todayDists->sum(fn($d) => $d->workers->count());
        $company->wage_today    = $company->workers_today * $company->daily_wage;

        // الشهر - filter على collection (لا query)
        $monthDists = $distributions->filter(
            fn($d) => Carbon::parse($d->distribution_date)->toDateString() >= $monthStart->toDateString()
                   && Carbon::parse($d->distribution_date)->toDateString() <= $monthEnd->toDateString()
        );
        $company->total_month       = $monthDists->sum(fn($d) => $d->workers->count() * $company->daily_wage);
        $company->days_worked_month = $monthDists->pluck('distribution_date')->unique()->count();

        // العمال الفريدين - filter على collection (لا query)
        $company->total_workers = $distributions
            ->flatMap(fn($d) => $d->workers->pluck('id'))
            ->unique()
            ->count();

        // الدفعات - filter على collection (لا query)
        $this->applyPaymentStats($company, $collections, $today);

        return $company;
    }

    /**
     * Apply payment statistics using loaded collections (no N+1)
     */
    private function applyPaymentStats(
        Company $company,
        SupportCollection $collections,
        Carbon $today
    ): void {
        // Last payment - filter على collection (لا query)
        $lastCollection = $collections
            ->where('is_paid', true)
            ->sortByDesc('payment_date')
            ->first();

        $company->last_payment_date = $lastCollection?->payment_date?->format('d M') ?? 'لم يتم';
        $company->amount_due        = $collections->where('is_paid', false)->sum('net_amount');

        // Unpaid collection - filter على collection (لا query)
        $unpaid = $collections
            ->where('is_paid', false)
            ->sortBy('period_end')
            ->first();

        if (!$unpaid) {
            $company->payment_status       = 'paid';
            $company->payment_status_label = 'تم التحصيل';
            $company->urgency_days         = 0;
            $company->urgency_label        = 'آخر دفعة: ' . $company->last_payment_date;
            return;
        }

        $dueDate      = Carbon::parse($unpaid->period_end)->addDays(7);
        $daysUntilDue = (int) $dueDate->diffInDays($today, false);

        // Use match for cleaner logic
        [$status, $label, $urgencyLabel] = match(true) {
            $daysUntilDue > 0   => ['upcoming',  $dueDate->format('d M'),                  'موعد الدفع: ' . $dueDate->format('d M')],
            $daysUntilDue === 0 => ['due',       'يستحق اليوم',                           'يستحق اليوم'],
            default             => ['overdue',   'متأخر ' . abs($daysUntilDue) . ' يوم',  'متأخر ' . abs($daysUntilDue) . ' يوم!'],
        };

        $company->payment_status       = $status;
        $company->payment_status_label = $label;
        $company->urgency_days         = abs($daysUntilDue);
        $company->urgency_label        = $urgencyLabel;
    }

    /**
     * Get company details for show page (no N+1)
     * Uses loadMissing to avoid reloading if already loaded
     */
    public function getCompanyDetails(Company $company): array
    {
        // loadMissing prevents reloading if already loaded
        $company->loadMissing([
            'distributions:id,company_id,distribution_date',
            'distributions.workers:id,name,phone',
            'payments:id,company_id,amount,date,payment_method,payment_type,created_at',
        ]);

        $today         = Carbon::today();
        $thisMonth     = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
        $thirtyDaysAgo = Carbon::today()->subDays(30);

        $distributions = $company->distributions;
        $payments      = $company->payments;

        // ✅ All filtering on pre-loaded data — zero queries
        $workersToday = $distributions
            ->filter(fn($d) => Carbon::parse($d->distribution_date)->toDateString() === $today->toDateString())
            ->flatMap(fn($d) => $d->workers)
            ->unique('id')
            ->values();

        $thisMonthDistributions = $distributions
            ->filter(fn($d) => Carbon::parse($d->distribution_date)->between($thisMonth[0], $thisMonth[1]))
            ->values();

        $monthlyTotal = $thisMonthDistributions
            ->sum(fn($d) => $d->workers->count() * $company->daily_wage);

        $distributionHistory = $distributions
            ->filter(fn($d) => Carbon::parse($d->distribution_date)->toDateString() >= $thirtyDaysAgo->toDateString())
            ->sortByDesc('distribution_date')
            ->values();

        $pendingAmount = 0;

        $paymentsHistory = $payments
            ->sortByDesc('created_at')
            ->values();

        return [
            'total_workers'        => $distributions->flatMap(fn($d) => $d->workers->pluck('id'))->unique()->count(),
            'pending_amount'       => $pendingAmount,
            'monthly_total'        => $monthlyTotal,
            'workers_today'        => $workersToday,
            'distribution_history' => $distributionHistory,
            'payments_history'     => $paymentsHistory,
        ];
    }

    /**
     * Create a new company
     */
    public function createCompany(array $data): Company
    {
        return $this->companyRepository->create($data);
    }

    /**
     * Update a company
     */
    public function updateCompany(Company $company, array $data): Company
    {
        return $this->companyRepository->update($company->id, $data);
    }

    /**
     * Delete a company
     */
    public function deleteCompany(Company $company): void
    {
        $this->companyRepository->delete($company->id);
    }

    /**
     * Get company as JSON (for API responses)
     */
    public function getCompanyAsJson(Company $company): array
    {
        return $company->only([
            'id', 'name', 'contact_person', 'phone', 'daily_wage',
            'payment_cycle', 'weekly_pay_day', 'is_active', 'notes',
        ]) + ['contract_start_date' => $company->contract_start_date?->format('Y-m-d')];
    }

    /**
     * Calculate global statistics for dashboard
     */
    private function calculateGlobalStats(SupportCollection $activeCompanies): array
    {
        return [
            'today_count'   => $activeCompanies->sum('workers_today'),
            'total_due'     => $activeCompanies->sum('amount_due'),
            'overdue_count' => $activeCompanies->where('payment_status', 'overdue')->count(),
            'active_count'  => $activeCompanies->count(),
        ];
    }
}
