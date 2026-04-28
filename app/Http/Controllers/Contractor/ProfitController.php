<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Services\ProfitService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfitController extends Controller
{
    public function __construct(
        private ProfitService $profitService,
    ) {}

    /**
     * Daily profit report.
     * Query param: ?date=YYYY-MM-DD  (defaults to today)
     */
    public function daily(Request $request): View
    {
        $date = $request->filled('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::today();

        $report = $this->profitService->getDailyReport(auth()->id(), $date);

        $prevDate = $date->copy()->subDay()->format('Y-m-d');
        $nextDate = $date->copy()->addDay()->format('Y-m-d');
        $isToday  = $date->isToday();

        return view('contractor.profit.daily', compact('report', 'date', 'prevDate', 'nextDate', 'isToday'));
    }

    /**
     * Monthly profit report.
     * Query params: ?month=M &year=YYYY  (defaults to current month/year)
     */
    public function monthly(Request $request): View
    {
        $month = (int) $request->input('month', now()->month);
        $year  = (int) $request->input('year', now()->year);

        $report = $this->profitService->getMonthlyReport(auth()->id(), $month, $year);

        $prevMonth = Carbon::createFromDate($year, $month, 1)->subMonth();
        $nextMonth = Carbon::createFromDate($year, $month, 1)->addMonth();

        return view('contractor.profit.monthly', compact(
            'report', 'month', 'year', 'prevMonth', 'nextMonth'
        ));
    }

    /**
     * Manual wage calculator — pure JS, no DB queries.
     */
    public function calculator(Request $request): View
    {
        return view('contractor.profit.calculator');
    }
}
