<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\DailyDistribution;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DistributionReportController extends Controller
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository,
    ) {}

    /**
     * عرض تقارير التوزيعات الشهرية والأسبوعية
     */
    public function index(Request $request): View
    {
        $contractorId = Auth::id();

        // الشهر والسنة من الـ request أو الشهر الحالي
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $companyId = $request->get('company_id');

        // جلب الشركات النشطة للفلتر
        $companies = $this->companyRepository->getActiveCompanies($contractorId);

        // نطاق التاريخ للشهر
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        // جلب التوزيعات مع التجميع
        $query = DailyDistribution::whereBetween('distribution_date', [$startDate, $endDate])
            ->where('contractor_id', $contractorId)
            ->whereNull('deleted_at');

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        $distributions = $query
            ->with(['company:id,name', 'workers:id'])
            ->get()
            ->groupBy('company_id');

        // تجميع البيانات حسب الشركة
        $report = $distributions->map(function ($companyDistributions) {
            $totalWorkers = $companyDistributions->sum(fn($d) => $d->workers->count());
            $totalAmount = $companyDistributions->sum('total_amount');

            // تقسيم التوزيعات إلى أسبوعية
            $weeklyData = $companyDistributions->groupBy(function ($d) {
                return $d->distribution_date->weekOfYear;
            });

            return [
                'company' => $companyDistributions->first()->company,
                'total_workers' => $totalWorkers,
                'total_amount' => $totalAmount,
                'distributions_count' => $companyDistributions->count(),
                'weekly_breakdown' => $weeklyData->map(function ($weekDistributions) {
                    return [
                        'week' => $weekDistributions->first()->distribution_date->weekOfYear,
                        'workers_count' => $weekDistributions->sum(fn($d) => $d->workers->count()),
                        'amount' => $weekDistributions->sum('total_amount'),
                        'count' => $weekDistributions->count(),
                    ];
                })->values(),
            ];
        });

        return view('contractor.distributions.reports', [
            'report' => $report,
            'companies' => $companies,
            'month' => $month,
            'year' => $year,
            'selectedCompanyId' => $companyId,
            'monthName' => $startDate->format('F Y'),
            'totalWorkers' => $report->sum('total_workers'),
            'totalAmount' => $report->sum('total_amount'),
        ]);
    }
}
