<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkerRequest;
use App\Http\Requests\UpdateWorkerRequest;
use App\Models\Advance;
use App\Repositories\Interfaces\WorkerRepositoryInterface;
use App\Services\AdvanceService;
use App\Services\WageCalculationService;
use App\Services\WorkerService;
use App\Services\AdvanceCollectionService;
use App\Services\OvertimeArchiveService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Repositories\Interfaces\OvertimeRepositoryInterface;

class WorkerController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        private WorkerRepositoryInterface $workerRepository,
        private WageCalculationService    $wageCalculationService,
        private WorkerService             $workerService,
        private AdvanceCollectionService  $advanceCollectionService,
        private AdvanceService            $advanceService,
        private OvertimeArchiveService    $overtimeArchiveService,
        private OvertimeRepositoryInterface $overtimeRepository,
    ) {}

    public function index(): View
    {
        $search  = request('search');
        $filter  = request('filter', 'all');
        $sort    = request('sort', 'assigned');
        $today   = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd   = $today->copy();

        // جيب كل الداتا في queries محدودة
        $allWorkers = $this->workerRepository->getAllByContractorWithFullData(Auth::id());

        // Enhance using loaded relations — zero extra queries
        $enhanced = $this->workerService->enhanceWorkersCollection(
            $allWorkers, $today, $monthStart, $monthEnd
        );

        $activeWorkers   = $enhanced->where('is_active', true)->values();
        $inactiveWorkers = $enhanced->where('is_active', false)->values();

        // Search filter
        if ($search) {
            $searchFn = fn($w) =>
                stripos($w->name, $search) !== false ||
                stripos((string) $w->id, $search) !== false ||
                stripos($w->phone ?? '', $search) !== false;

            $activeWorkers   = $activeWorkers->filter($searchFn)->values();
            $inactiveWorkers = $inactiveWorkers->filter($searchFn)->values();
        }

        // Status filter
        if ($filter === 'inactive') {
            $workers = $inactiveWorkers;
        } else {
            $activeWorkers = match($filter) {
                'assigned'   => $activeWorkers->filter(fn($w) => $w->assigned_today)->values(),
                'unassigned' => $activeWorkers->filter(fn($w) => !$w->assigned_today)->values(),
                'advance'    => $activeWorkers->filter(fn($w) => $w->has_pending_advance)->values(),
                default      => $activeWorkers,
            };

            // Sort
            $workers = match($sort) {
                'name' => $activeWorkers->sortBy(fn($w) => $w->name)->values(),
                'attendance' => $activeWorkers->sortByDesc(fn($w) => $w->attendance_rate ?? 0)->values(),
                default => $activeWorkers->sortBy(fn($w) => $w->assigned_today ? 0 : 1)->values(),
            };
        }

        return view('contractor.workers.index', [
            'workers'        => $workers,
            'inactiveWorkers'=> $inactiveWorkers,
            'search'         => $search,
            'filter'         => $filter,
            'total_workers'  => $enhanced->where('is_active', true)->count(),
            'assigned_today' => $workers->filter(fn($w) => $w->assigned_today)->count(),
            'has_advances'   => $workers->filter(fn($w) => $w->has_pending_advance)->count(),
            'unassigned'     => $workers->filter(fn($w) => !$w->assigned_today)->count(),
            'inactive_count' => $inactiveWorkers->count(),
        ]);
    }

    public function create(): View
    {
        return view('contractor.workers.create');
    }

    public function store(StoreWorkerRequest $request): JsonResponse|RedirectResponse
    {
        $worker = $this->workerRepository->create([
            ...$request->validated(),
            'contractor_id' => Auth::id(),
        ]);

        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => 'تم إضافة العامل بنجاح', 'worker' => $worker])
            : redirect()->route('contractor.workers.index')->with('success', 'تم إضافة العامل بنجاح');
    }

    public function show($id): JsonResponse|View
    {
        \Log::info("Attempting to load worker: {$id} for user: " . Auth::id());
        \Log::info("Request expects JSON: " . (request()->expectsJson() ? 'yes' : 'no'));
        \Log::info("Request headers:", ['accept' => request()->header('Accept')]);
        
        $worker = $this->workerRepository->findByIdWithFullData($id);
        
        \Log::info("Worker loaded:", ['worker' => $worker ? $worker->toArray() : null]);
      
        // Check if worker exists
        if (!$worker) {
            \Log::warning("Worker not found: {$id}");
            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => 'العامل غير موجود'], 404)
                : abort(404, 'العامل غير موجود');
        }
        
        // Check authorization
        try {
            $this->authorize('view', $worker);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::warning("Authorization failed for worker: {$id}", ['userId' => Auth::id(), 'contractorId' => $worker->contractor_id]);
            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => 'ليس لديك صلاحية للوصول إلى هذا العامل'], 403)
                : abort(403, 'Unauthorized');
        }
        
        if (request()->expectsJson()) {
            $response = [
                'success' => true,
                'id'          => $worker->id,
                'name'        => $worker->name,
            ];
            \Log::info("Returning JSON response:", $response);
            return response()->json($response);
        }

        $from   = Carbon::today()->subDays(30)->toDateString();
        $to     = Carbon::today()->toDateString();
        $ledger = $this->wageCalculationService->getWorkerLedger($id, $from, $to);

        // Build calendar from loaded relations — zero queries
        $calendarData = $this->workerService->buildAttendanceCalendar($worker);
        $classMap     = ['full' => 'c-present', 'partial' => 'c-partial', 'absent' => 'c-absent'];
        $calendar     = array_map(fn($day) => [
            'day'   => $day['day'],
            'class' => $day['isToday'] ? 'c-today' : ($classMap[$day['status']] ?? 'c-empty'),
        ], $calendarData['days']);

        $ledger += [
            'attendance_days' => $calendarData['summary']['fullDays'],
            'partial_days'    => $calendarData['summary']['partialDays'],
            'absent_days'     => $calendarData['summary']['absentDays'],
            'attendance_rate' => $calendarData['summary']['attendanceRate'],
        ];

        // Build show page data from loaded relations — zero queries
        $showData = $this->workerService->buildShowPageData($worker);

        // Get current week overtime and archives
        $currentWeekOvertime = $this->overtimeRepository->getCurrentWeekOvertimeCount($worker->id);
        $overtimeArchives = $worker->overtimeArchives()->orderBy('week_end', 'desc')->get();

        return view('contractor.workers.show', [
            'worker'             => $worker,
            'ledger'             => $ledger,
            'calendar'           => $calendar,
            'frequentCompanies'  => $showData['frequentCompanies'],
            'thisWeekActivity'   => $showData['thisWeekActivity'],
            'deductionsTimeline' => $showData['deductionsTimeline'],
            'pendingAdvances'    => $showData['pendingAdvances'],
            'collectedAdvances'  => $showData['collectedAdvances'],
            'currentWeekOvertime' => $currentWeekOvertime,
            'overtimeArchives'   => $overtimeArchives,
        ]);
    }

    public function edit($id): JsonResponse|View
    {
        $worker = $this->workerRepository->findById($id);
        $this->authorize('update', $worker);

        return request()->expectsJson()
            ? response()->json(['success' => true, 'worker' => $worker])
            : view('contractor.workers.edit', compact('worker'));
    }

    public function update(UpdateWorkerRequest $request, $id): JsonResponse|RedirectResponse
    {
        $worker = $this->workerRepository->findById($id);
        $this->authorize('update', $worker);

        $updated = $this->workerRepository->update($id, $request->validated());

        $message = ($request->has('is_active') && count($request->validated()) === 1)
            ? ($request->boolean('is_active') ? 'تم تفعيل العامل بنجاح' : 'تم إيقاف العامل بنجاح')
            : 'تم تحديث العامل بنجاح';

        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => $message, 'worker' => $updated])
            : redirect()->route('contractor.workers.show', $id)->with('success', $message);
    }

    public function destroy($id)
    {
        $worker = $this->workerRepository->findById($id);
        $this->authorize('delete', $worker);

        // Soft deactivate — intentional business rule
        $this->workerRepository->deactivate($id);

        return request()->expectsJson() || request()->header('Accept') === 'application/json'
            ? response()->json([
                'success' => true,
                'message' => 'تم إيقاف العامل بنجاح'
            ])
            : redirect()->route('contractor.workers.index')
                ->with('success', 'تم إيقاف العامل بنجاح');
    }

    /**
     * Record a payment for a worker.
     */
    public function recordPayment($id): JsonResponse
    {
        $worker = $this->workerRepository->findById($id);
        $this->authorize('update', $worker);

        $validated = request()->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'payment_method' => 'nullable|in:cash,transfer,check,other',
            'payment_type' => 'nullable|in:salary,advance_repayment,bonus,other',
            'notes' => 'nullable|string|max:500',
        ]);

        $from = Carbon::today()->subDays(30)->toDateString();
        $to = Carbon::today()->toDateString();
        $ledger = $this->wageCalculationService->getWorkerLedger($id, $from, $to);
        $remainingBalanceBeforePayment = (float) ($ledger['remaining_balance'] ?? 0);
        $overpaidAmount = max(0, (float) $validated['amount'] - $remainingBalanceBeforePayment);

        $payment = DB::transaction(function () use ($worker, $id, $validated, $overpaidAmount) {
            $payment = $worker->payments()->create([
                'contractor_id' => Auth::id(),
                'amount' => $validated['amount'],
                'date' => $validated['date'],
                'payment_method' => $validated['payment_method'] ?? null,
                'payment_type' => $validated['payment_type'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Archive overtime hours for this week
            $this->overtimeArchiveService->archiveWeeklyOvertimes($worker, $payment);

            // Still collect pending advances from this payment amount
            $this->advanceCollectionService->collectAdvancesFromPayment(
                $id,
                (float) $validated['amount'],
                $validated['date']
            );

            // Any amount above the current due becomes a new advance
            if ($overpaidAmount > 0) {
                $this->advanceService->recordAdvance([
                    'worker_id' => $id,
                    'amount' => $overpaidAmount,
                    'date' => $validated['date'],
                    'reason' => 'زيادة عن المستحق أثناء القبض',
                    'recovery_method' => 'immediately',
                ], Auth::id());
            }

            return $payment;
        });

        $response = [
            'success' => true,
            'message' => 'تم تسجيل الدفع بنجاح',
            'payment' => $payment,
        ];

        if ($overpaidAmount > 0) {
            $response['warning'] = 'تم دفع مبلغ أكبر من المستحق، وتم تحويل الزيادة إلى سلفة بقيمة ' . number_format($overpaidAmount, 2) . ' ج';
            $response['overpaid_amount'] = $overpaidAmount;
        }

        return response()->json($response);
    }
}

