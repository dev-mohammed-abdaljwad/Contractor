<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkerRequest;
use App\Http\Requests\UpdateWorkerRequest;
use App\Repositories\Interfaces\WorkerRepositoryInterface;
use App\Services\WageCalculationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    public function __construct(
        private WorkerRepositoryInterface $workerRepository,
        private WageCalculationService $wageCalculationService,
    ) {}

    public function index()
    {
        $search = request('search');
        $filter = request('filter', 'all'); // all, assigned, unassigned, has_advance
        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today;
        $daysInMonthSoFar = $monthEnd->diffInDays($monthStart) + 1;

        // Get all workers for this contractor
        $allWorkers = $this->workerRepository->getAllByContractor(Auth::id());

        // Separate into active and inactive
        $activeWorkers = $allWorkers->where('is_active', true)->values();
        $inactiveWorkers = $allWorkers->where('is_active', false)->values();

        // Helper function to enhance worker data with advances info
        $enhanceWorker = function ($worker) use ($today, $monthStart, $monthEnd, $daysInMonthSoFar) {
            $todayDistribution = $worker->distributions()
                ->where('distribution_date', $today)
                ->with('company')
                ->first();

            // Set today's assignment flags
            $worker->assigned_today = $todayDistribution;
            $worker->distribution_today = (bool)$todayDistribution;
            $worker->assigned_company = $todayDistribution?->company->name ?? null;
            $worker->company_today = $todayDistribution?->company->name ?? null;
            $worker->daily_wage = $todayDistribution?->company->daily_wage ?? 0;

            // Calculate work days
            $daysWorked = $worker->distributions()
                ->whereBetween('distribution_date', [$monthStart, $monthEnd])
                ->select('distribution_date')
                ->distinct()
                ->count();

            $worker->attendance_rate = $daysInMonthSoFar > 0 ? round(($daysWorked / $daysInMonthSoFar) * 100) : 0;
            $worker->days_worked = $daysWorked;

            // Get last worked date
            $lastWork = $worker->distributions()
                ->orderByDesc('distribution_date')
                ->first();
            $worker->last_worked_date = $lastWork?->distribution_date?->format('d/m/Y') ?? 'لم يعمل بعد';

            // Get pending advances
            $pendingAdvances = $worker->advances()
                ->where('is_settled', false)
                ->get();
            $worker->has_pending_advance = $pendingAdvances->count() > 0;
            $worker->pending_advance_amount = $pendingAdvances->sum('amount');

            // Get today's deductions
            $todayDeductions = $worker->deductions()
                ->where('deduction_date', $today)
                ->get();
            $worker->has_deduction = $todayDeductions->count() > 0;
            $worker->deduction_amount = $todayDeductions->sum('amount');

            return $worker;
        };

        // Enhance active workers
        $activeWorkers = $activeWorkers->map($enhanceWorker);

        // Apply search filter to active workers
        if ($search) {
            $activeWorkers = $activeWorkers->filter(fn($w) => 
                stripos($w->name, $search) !== false || 
                stripos((string)$w->id, $search) !== false ||
                stripos($w->phone, $search) !== false
            );
        }

        // Apply status filter to active workers
        $activeWorkers = $this->applyStatusFilter($activeWorkers, $filter);

        // Sort active workers: assigned first, then unassigned
        $activeWorkers = $activeWorkers->sortBy(function ($worker) {
            return $worker->assigned_today ? 0 : 1;
        })->values();

        // Enhance inactive workers (for management purposes)
        $inactiveWorkers = $inactiveWorkers->map($enhanceWorker);

        // Apply search filter to inactive workers
        if ($search) {
            $inactiveWorkers = $inactiveWorkers->filter(fn($w) => 
                stripos($w->name, $search) !== false || 
                stripos((string)$w->id, $search) !== false ||
                stripos($w->phone, $search) !== false
            );
        }

        // Calculate statistics
        $total_workers = $allWorkers->where('is_active', true)->count();
        $assigned_today = $activeWorkers->filter(fn($w) => $w->assigned_today)->count();
        $has_advances = $activeWorkers->filter(fn($w) => $w->has_pending_advance)->count();
        $unassigned = $activeWorkers->filter(fn($w) => !$w->assigned_today)->count();
        $inactive_count = $inactiveWorkers->count();

        // Use 'workers' as the variable name for the template
        $workers = $activeWorkers;

        return view(
            'contractor.workers.index',
            compact('workers', 'inactiveWorkers', 'search', 'filter', 'total_workers', 'assigned_today', 'has_advances', 'unassigned', 'inactive_count')
        );
    }

    /**
     * Apply status filter to workers collection
     */
    private function applyStatusFilter($workers, $filter)
    {
        return match($filter) {
            'assigned' => $workers->filter(fn($w) => $w->assigned_today),
            'unassigned' => $workers->filter(fn($w) => !$w->assigned_today),
            'has_advance' => $workers->filter(fn($w) => $w->has_pending_advance),
            default => $workers, // 'all'
        };
    }

    public function create()
    {
        return view('contractor.workers.create');
    }

    public function store(StoreWorkerRequest $request)
    {
        $worker = $this->workerRepository->create([
            ...$request->validated(),
            'contractor_id' => Auth::id(),
        ]);

        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة العامل بنجاح',
                'worker' => $worker
            ]);
        }

        return redirect()->route('contractor.workers.index')
            ->with('success', 'تم إضافة العامل بنجاح');
    }

    public function show($id)
    {
        $worker = $this->workerRepository->findById($id);
        
        if (!$worker || $worker->contractor_id !== Auth::id()) {
            abort(403);
        }

        // Return JSON if requested for modal edit
        if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
            return response()->json([
                'id' => $worker->id,
                'name' => $worker->name,
                'phone' => $worker->phone,
                'national_id' => $worker->national_id,
                'joined_date' => $worker->joined_date?->format('Y-m-d'),
                'is_active' => $worker->is_active,
            ]);
        }

        // Get worker ledger for last 30 days
        $from = Carbon::today()->subDays(30)->toDateString();
        $to = Carbon::today()->toDateString();
        $ledger = $this->wageCalculationService->getWorkerLedger($id, $from, $to);
        
        // Build monthly attendance calendar for current month
        $calendarData = $this->buildAttendanceCalendar($worker);
        
        // Add calendar summary to ledger
        $ledger['attendance_days'] = $calendarData['summary']['fullDays'];
        $ledger['partial_days'] = $calendarData['summary']['partialDays'];
        $ledger['absent_days'] = $calendarData['summary']['absentDays'];
        $ledger['attendance_rate'] = $calendarData['summary']['attendanceRate'];
        
        // Format calendar for view - convert status to class
        $calendar = [];
        foreach ($calendarData['days'] as $day) {
            $classMap = [
                'full' => 'c-present',
                'partial' => 'c-partial',
                'absent' => 'c-absent',
            ];
            $calendar[] = [
                'day' => $day['day'],
                'class' => $day['isToday'] ? 'c-today' : ($classMap[$day['status']] ?? 'c-empty'),
            ];
        }

        // Get frequent companies for this month
        $monthStart = Carbon::today()->startOfMonth();
        $monthEnd = Carbon::today();
        
        $frequentCompanies = $worker->distributions()
            ->whereBetween('distribution_date', [$monthStart, $monthEnd])
            ->with('company')
            ->get()
            ->groupBy('company_id')
            ->map(function($distributions) {
                $company = $distributions->first()->company;
                $totalDays = $distributions->count();
                
                return [
                    'name' => $company->name ?? 'شركة غير معروفة',
                    'days' => $totalDays,
                    'percentage' => $totalDays, // Will be normalized by view
                ];
            })
            ->sortByDesc('days')
            ->take(5)
            ->values()
            ->tap(function($companies) {
                // Normalize percentages based on max days
                $maxDays = $companies->max('days') ?? 1;
                $companies->each(function(&$company) use ($maxDays) {
                    $company['percentage'] = ($company['days'] / $maxDays) * 100;
                });
            });

        // Get this week activity
        $weekStart = Carbon::today()->subDays(6);
        $thisWeekActivity = $worker->distributions()
            ->whereBetween('distribution_date', [$weekStart, Carbon::today()])
            ->with('company')
            ->orderBy('distribution_date', 'desc')
            ->get()
            ->map(function($dist) use ($worker) {
                $hasDeduction = $worker->deductions()
                    ->where('deduction_date', $dist->distribution_date)
                    ->first();
                
                $day = $dist->distribution_date->day;
                $dayName = $dist->distribution_date->locale('ar')->dayName;
                
                return [
                    'day' => $day,
                    'day_name' => $dayName,
                    'company_name' => $dist->company->name ?? 'غير محدد',
                    'rate_label' => number_format($dist->company->daily_wage) . ' ج/يوم',
                    'amount' => number_format($dist->company->daily_wage, 0),
                    'status' => $hasDeduction ? 'partial' : 'full',
                ];
            })->values();

        // Get deductions timeline
        $deductionsTimeline = $worker->deductions()
            ->orderBy('deduction_date', 'desc')
            ->with('company')
            ->get()
            ->map(function($ded) {
                $typeLabel = match($ded->type) {
                    'full' => 'خصم يوم كامل',
                    'half' => 'خصم نصف يوم',
                    'quarter' => 'خصم ربع يوم',
                    'reversal' => 'إلغاء خصم',
                    default => 'خصم'
                };

                return [
                    'title' => $typeLabel,
                    'date' => $ded->deduction_date?->format('d/m/Y') ?? '-',
                    'company_name' => $ded->company?->name ?? '-',
                    'amount' => (int)$ded->amount,
                    'reason' => $ded->reason ?? '-',
                    'type' => $ded->type,
                    'original_amount' => (int)$ded->amount,
                ];
            })->values();

        // Get pending and collected advances
        $pendingAdvances = $worker->advances()
            ->where('is_settled', false)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($adv) {
                return [
                    'amount' => (int)$adv->amount,
                    'date' => $adv->advance_date?->format('d M Y') ?? $adv->created_at?->format('d M Y') ?? '-',
                    'recovery_method' => 'خصم من أول دفعة · لم يُحصَّل',
                ];
            })->values();

        $collectedAdvances = $worker->advances()
            ->where('is_settled', true)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function($adv) {
                return [
                    'amount' => (int)$adv->amount,
                    'date' => $adv->advance_date?->format('d M Y') ?? $adv->created_at?->format('d M Y') ?? '-',
                    'collected_date' => $adv->updated_at?->format('d M Y') ?? '-',
                ];
            })->values();

        return view('contractor.workers.show', compact(
            'worker',
            'ledger',
            'calendar',
            'frequentCompanies',
            'thisWeekActivity',
            'deductionsTimeline',
            'pendingAdvances',
            'collectedAdvances'
        ));
    }

    /**
     * Build attendance calendar data for the current month
     */
    private function buildAttendanceCalendar($worker)
    {
        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();

        // Get all distributions for current month
        $distributions = $worker->distributions()
            ->whereBetween('distribution_date', [$monthStart, $monthEnd])
            ->with('company')
            ->get()
            ->keyBy(fn($d) => $d->distribution_date->format('Y-m-d'));

        // Get all deductions for current month
        $deductions = $worker->deductions()
            ->whereBetween('deduction_date', [$monthStart, $monthEnd])
            ->get()
            ->keyBy(fn($d) => $d->deduction_date->format('Y-m-d'));

        // Build calendar for all days in month
        $days = [];
        $fullDays = 0;
        $partialDays = 0;
        $absentDays = 0;

        for ($day = 1; $day <= $monthEnd->day; $day++) {
            $date = $monthStart->copy()->addDays($day - 1);
            $dateStr = $date->format('Y-m-d');
            
            $hasDistribution = isset($distributions[$dateStr]);
            $hasDeduction = isset($deductions[$dateStr]);
            
            // Determine day status
            if ($hasDistribution && !$hasDeduction) {
                $status = 'full'; // Green - full day present
                $fullDays++;
            } elseif ($hasDistribution && $hasDeduction) {
                $status = 'partial'; // Yellow - partial day
                $partialDays++;
            } else {
                $status = 'absent'; // Red - absent / no distribution
                $absentDays++;
            }

            $days[] = [
                'day' => $day,
                'date' => $dateStr,
                'status' => $status,
                'isToday' => $date->format('Y-m-d') === $today->format('Y-m-d'),
                'distribution' => $distributions[$dateStr] ?? null,
                'deduction' => $deductions[$dateStr] ?? null,
            ];
        }

        $daysWorkedThisMonth = $fullDays + $partialDays;
        $daysInMonth = $monthEnd->day;
        $attendanceRate = $daysInMonth > 0 ? round(($daysWorkedThisMonth / $daysInMonth) * 100) : 0;

        return [
            'month' => $monthStart->format('Y-m'),
            'monthName' => $this->getArabicMonthName($monthStart),
            'days' => $days,
            'summary' => [
                'fullDays' => $fullDays,
                'partialDays' => $partialDays,
                'absentDays' => $absentDays,
                'attendanceRate' => $attendanceRate,
            ],
        ];
    }

    /**
     * Get Arabic month name
     */
    private function getArabicMonthName(Carbon $date)
    {
        $months = [
            'January' => 'يناير',
            'February' => 'فبراير',
            'March' => 'مارس',
            'April' => 'أبريل',
            'May' => 'مايو',
            'June' => 'يونيو',
            'July' => 'يوليو',
            'August' => 'أغسطس',
            'September' => 'سبتمبر',
            'October' => 'أكتوبر',
            'November' => 'نوفمبر',
            'December' => 'ديسمبر',
        ];

        $monthName = $date->format('F');
        $year = $date->format('Y');
        
        return ($months[$monthName] ?? $monthName) . ' ' . $year;
    }

    public function edit($id)
    {
        $worker = $this->workerRepository->findById($id);
        
        if (!$worker || $worker->contractor_id !== Auth::id()) {
            abort(403);
        }

        return view('contractor.workers.edit', compact('worker'));
    }

    public function update(UpdateWorkerRequest $request, $id)
    {
        $worker = $this->workerRepository->findById($id);
        
        if (!$worker || $worker->contractor_id !== Auth::id()) {
            abort(403);
        }

        $updated = $this->workerRepository->update($id, $request->validated());

        // Determine success message based on what was updated
        $message = match(true) {
            $request->has('is_active') && count($request->validated()) === 1 => 
                ($request->input('is_active') ? 'تم تفعيل العامل بنجاح' : 'تم إيقاف العامل بنجاح'),
            default => 'تم تحديث العامل بنجاح'
        };

        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'message' => $message,
                'worker' => $updated
            ]);
        }

        return redirect()->route('contractor.workers.show', $id)
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $worker = $this->workerRepository->findById($id);
        
        if (!$worker || $worker->contractor_id !== Auth::id()) {
            abort(403);
        }

        $this->workerRepository->update($id, ['is_active' => false]);

        return redirect()->route('contractor.workers.index')
            ->with('success', 'تم حذف العامل بنجاح');
    }
}
