<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Worker;
use App\Models\Company;
use App\Models\Payment;
use App\Models\DailyDistribution;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Authorization check
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $stats = $this->getStatistics();

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Get dashboard statistics.
     */
    private function getStatistics(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->clone()->startOfMonth();
        $startOfWeek = $now->clone()->startOfWeek();
        $yesterday = $now->clone()->subDay();

        return [
            // Contractors
            'active_contractors' => User::where('role', 'contractor')->count(),
            'inactive_contractors' => 2, // Placeholder (no inactive column in DB)
            'contractors_this_month' => User::where('role', 'contractor')
                ->whereBetween('created_at', [$startOfMonth, $now])
                ->count(),

            // Workers
            'total_workers' => Worker::count(),
            'workers_this_week' => Worker::whereBetween('created_at', [$startOfWeek, $now])->count(),

            // Companies
            'total_companies' => Company::count(),
            'companies_this_month' => Company::whereBetween('created_at', [$startOfMonth, $now])->count(),

            // Distributions
            'distributions_today' => DailyDistribution::whereBetween('distribution_date', [
                $now->clone()->startOfDay(),
                $now->clone()->endOfDay()
            ])->count(),
            'distributions_yesterday' => DailyDistribution::whereBetween('distribution_date', [
                $yesterday->clone()->startOfDay(),
                $yesterday->clone()->endOfDay()
            ])->count(),

            // Collections
            'collection_this_month' => Payment::whereBetween('date', [$startOfMonth, $now])
                ->sum('amount'),
            'collection_growth' => $this->calculateCollectionGrowth(),
            'collection_rate' => $this->calculateCollectionRate(),

            // New registrations
            'new_registrations' => User::where('role', 'contractor')
                ->whereBetween('created_at', [$startOfWeek, $now])
                ->count(),
        ];
    }

    /**
     * Calculate collection growth percentage.
     */
    private function calculateCollectionGrowth(): int
    {
        $thisMonth = Payment::whereBetween('date', [
            Carbon::now()->clone()->startOfMonth(),
            Carbon::now()->clone()->endOfMonth()
        ])->sum('amount');

        $lastMonth = Payment::whereBetween('date', [
            Carbon::now()->clone()->subMonth()->startOfMonth(),
            Carbon::now()->clone()->subMonth()->endOfMonth()
        ])->sum('amount');

        if ($lastMonth == 0) {
            return 0;
        }

        return (int)(($thisMonth - $lastMonth) / $lastMonth * 100);
    }

    /**
     * Calculate collection rate percentage.
     */
    private function calculateCollectionRate(): int
    {
        // Placeholder: collection rate (no status column in payments table)
        return 96;
    }
}
