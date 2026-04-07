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

        // Single optimized query for today's distributions with eager loading
        $todayDistributions = DailyDistribution::where('contractor_id', $contractorId)
            ->where('distribution_date', $today)
            ->with(['company', 'workers']) // Eager load relationships
            ->get();

        // Get active companies optimized
        $activeCompanies = Company::where('contractor_id', $contractorId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Calculate wages in single query result (from already loaded distributions)
        $totalWagesToday = 0;
        foreach ($todayDistributions as $dist) {
            $totalWagesToday += $dist->workers->count() * $dist->company->daily_wage;
        }

        // Get today's companies with distribution data (no N+1 - using eager loaded data)
        $companiesWithDistributions = $activeCompanies->map(function ($company) use ($todayDistributions) {
            // Filter from already loaded distributions
            $companyDistributions = $todayDistributions->filter(fn($d) => $d->company_id === $company->id);
            
            $totalWage = 0;
            $workersCount = 0;
            foreach ($companyDistributions as $dist) {
                $workersCount += $dist->workers->count();
                $totalWage += $dist->workers->count() * $company->daily_wage;
            }

            return [
                'company' => $company,
                'workers_count' => $workersCount,
                'total_wage' => $totalWage,
            ];
        });

        // Get pending collections with eager loading
        $pendingCollections = Collection::where('contractor_id', $contractorId)
            ->where('is_paid', false)
            ->with('company') // Eager load company
            ->orderBy('period_end', 'desc')
            ->get();

        // Get total workers count without loading all workers
        $totalWorkersCount = Auth::user()->workers()->count() ?? 0;

        return view('dashboard', [
            'workersDistributedToday' => $todayDistributions->count(),
            'totalWorkersCount' => $totalWorkersCount,
            'activeCompaniesCount' => $activeCompanies->count(),
            'totalWagesToday' => $totalWagesToday,
            'companiesWithDistributions' => $companiesWithDistributions,
            'pendingCollections' => $pendingCollections,
            'todayDistributions' => $todayDistributions,
        ]);
    }
}
