<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkerRequest;
use App\Http\Requests\UpdateWorkerRequest;
use App\Repositories\Interfaces\WorkerRepositoryInterface;
use App\Services\WageCalculationService;
use App\Services\WorkerService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WorkerController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        private WorkerRepositoryInterface $workerRepository,
        private WageCalculationService    $wageCalculationService,
        private WorkerService             $workerService,
    ) {}

    public function index(): View
    {
        $search  = request('search');
        $filter  = request('filter', 'all');
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
        $activeWorkers = match($filter) {
            'assigned'   => $activeWorkers->filter(fn($w) => $w->assigned_today)->values(),
            'unassigned' => $activeWorkers->filter(fn($w) => !$w->assigned_today)->values(),
            'has_advance'=> $activeWorkers->filter(fn($w) => $w->has_pending_advance)->values(),
            default      => $activeWorkers,
        };

        // Sort: assigned first
        $workers = $activeWorkers->sortBy(fn($w) => $w->assigned_today ? 0 : 1)->values();

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
        $worker = $this->workerRepository->findByIdWithFullData($id);
        $this->authorize('view', $worker);

        if (request()->expectsJson()) {
            return response()->json([
                'id'          => $worker->id,
                'name'        => $worker->name,
                'phone'       => $worker->phone,
                'national_id' => $worker->national_id,
                'joined_date' => $worker->joined_date?->format('Y-m-d'),
                'is_active'   => $worker->is_active,
            ]);
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

        return view('contractor.workers.show', [
            'worker'             => $worker,
            'ledger'             => $ledger,
            'calendar'           => $calendar,
            'frequentCompanies'  => $showData['frequentCompanies'],
            'thisWeekActivity'   => $showData['thisWeekActivity'],
            'deductionsTimeline' => $showData['deductionsTimeline'],
            'pendingAdvances'    => $showData['pendingAdvances'],
            'collectedAdvances'  => $showData['collectedAdvances'],
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

    public function destroy($id): RedirectResponse
    {
        $worker = $this->workerRepository->findById($id);
        $this->authorize('delete', $worker);

        // Soft deactivate — intentional business rule
        $this->workerRepository->deactivate($id);

        return redirect()->route('contractor.workers.index')
            ->with('success', 'تم إيقاف العامل بنجاح');
    }
}

