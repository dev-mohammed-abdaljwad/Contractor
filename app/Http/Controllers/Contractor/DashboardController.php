<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\DailyDistribution;
use App\Models\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $contractorId = Auth::id();
        $today = Carbon::today()->toDateString();

        // Get today's statistics
        $todayDistributions = DailyDistribution::where('contractor_id', $contractorId)
            ->where('distribution_date', $today)
            ->with(['company', 'worker'])
            ->get();

        $activeCompanies = Company::where('contractor_id', $contractorId)
            ->where('is_active', true)
            ->get();

        $totalWagesToday = $todayDistributions->sum('daily_wage_snapshot');

        // Get today's companies with distribution data
        $companiesWithDistributions = $activeCompanies->map(function ($company) use ($today) {
            $distributions = DailyDistribution::where('company_id', $company->id)
                ->where('distribution_date', $today)
                ->count();
            $totalWage = DailyDistribution::where('company_id', $company->id)
                ->where('distribution_date', $today)
                ->sum('daily_wage_snapshot');

            return [
                'company' => $company,
                'workers_count' => $distributions,
                'total_wage' => $totalWage,
            ];
        });

        // Get pending collections
        $pendingCollections = Collection::where('contractor_id', $contractorId)
            ->where('is_paid', false)
            ->with('company')
            ->orderBy('period_end', 'desc')
            ->get();

        return view('dashboard', [
            'workersDistributedToday' => $todayDistributions->count(),
            'totalWorkersCount' => Auth::user()->workers()->count() ?? 0,
            'activeCompaniesCount' => $activeCompanies->count(),
            'totalWagesToday' => $totalWagesToday,
            'companiesWithDistributions' => $companiesWithDistributions,
            'pendingCollections' => $pendingCollections,
            'todayDistributions' => $todayDistributions,
        ]);
    }
}
