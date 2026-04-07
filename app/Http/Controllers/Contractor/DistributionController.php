<?php

namespace App\Http\Controllers\Contractor;

use App\Exceptions\DuplicateDistributionException;
use App\Http\Controllers\Controller;
use App\Models\DailyDistribution;
use App\Models\Worker;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Repositories\Interfaces\DistributionRepositoryInterface;
use App\Repositories\Interfaces\WorkerRepositoryInterface;
use App\Services\DistributionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DistributionController extends Controller
{
    public function __construct(
        private DistributionService             $distributionService,
        private DistributionRepositoryInterface $distributionRepository,
        private CompanyRepositoryInterface      $companyRepository,
        private WorkerRepositoryInterface       $workerRepository,
    ) {}

    public function index(): View
    {
        $contractorId  = Auth::id();
        $distributions = $this->distributionRepository->getAllByContractor($contractorId);

        return view('contractor.distributions.index', [
            'distributions'      => $distributions,
            'totalDistributions' => $distributions->count(),
            'totalWorkers'       => $distributions->sum('workers_count'),
            'totalWages'         => $distributions->sum('total_amount'),
            'editableCount'      => $distributions->filter(fn($d) => $d->canEdit())->count(),
            'companies'          => $this->companyRepository->getActiveCompanies($contractorId),
            'workers'            => $this->workerRepository->getActiveWorkers($contractorId),
        ]);
    }

    public function create(): View
    {
        $contractorId = Auth::id();

        return view('contractor.distributions.create', [
            'companies' => $this->companyRepository->getActiveCompanies($contractorId),
            'workers'   => $this->workerRepository->getActiveWorkers($contractorId),
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'company_id'    => 'required|exists:companies,id',
            'worker_ids'    => 'required|array|min:1',
            'worker_ids.*'  => 'exists:workers,id',
        ]);

        try {
            $this->distributionService->distributeWorkers(
                contractorId: Auth::id(),
                companyId:    $validated['company_id'],
                workerIds:    $validated['worker_ids'],
                date:         now()->toDateString(),
            );

            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => 'تم إنشاء التوزيع بنجاح'])
                : redirect()->route('contractor.distributions.index')->with('success', 'تم إنشاء التوزيع بنجاح!');

        } catch (DuplicateDistributionException $e) {
            $workerName = Worker::select('name')->find($e->getWorkerId())?->name ?? 'عامل';
            $message    = "العامل {$workerName} مسجل بالفعل لشركة أخرى اليوم";

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : redirect()->back()->with('error', $message);
        }
    }

    public function show(int $id): View
    {
        $distribution = $this->distributionService->getDistributionHistory($id);

        return view('contractor.distributions.show', compact('distribution'));
    }

    public function edit(DailyDistribution $distribution): RedirectResponse|View
    {
        if (!$distribution->canEdit()) {
            return back()->with('error', 'لا يمكن تعديل التوزيع بعد مرور 7 أيام');
        }

        return view('contractor.distributions.edit', [
            'distribution' => $distribution,
            'workers'      => $this->workerRepository->getActiveWorkers(Auth::id()),
        ]);
    }

    public function update(Request $request, DailyDistribution $distribution): RedirectResponse
    {
        $validated = $request->validate([
            'worker_ids'   => 'required|array|min:1',
            'worker_ids.*' => 'exists:workers,id',
        ]);

        $this->distributionService->updateDistribution($distribution, $validated['worker_ids']);

        return redirect()->route('contractor.distributions.show', $distribution)
            ->with('success', 'تم تحديث التوزيع بنجاح!');
    }

    public function destroy(DailyDistribution $distribution): RedirectResponse
    {
        if (!$distribution->canEdit()) {
            return back()->with('error', 'لا يمكن إلغاء التوزيع بعد مرور 7 أيام');
        }

        $this->distributionService->cancelDistribution($distribution, Auth::id());

        return redirect()->route('contractor.distributions.index')
            ->with('success', 'تم إلغاء التوزيع بنجاح!');
    }

    public function calculateEarnings(): JsonResponse
    {
        try {
            $earnings = $this->distributionService->calculateRealTimeEarnings(
                companyId:  (int) request('company_id'),
                workerIds:  request('worker_ids', []),
                date:       request('date', Carbon::today()->toDateString()),
            );

            return response()->json(['success' => true, 'data' => $earnings]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function getAssignedWorkers(): JsonResponse
    {
        $date         = request('date', Carbon::today()->toDateString());
        $distributions = $this->distributionRepository->getByDateAndContractor($date, Auth::id());

        $assigned = $distributions->flatMap(fn($dist) =>
            $dist->workers->map(fn($worker) => [
                'id'           => $dist->id,
                'worker_id'    => $worker->id,
                'worker_name'  => $worker->name,
                'company_id'   => $dist->company_id,
                'company_name' => $dist->company->name,
            ])
        );

        return response()->json(['success' => true, 'data' => $assigned]);
    }

    public function getAvailableWorkers(): JsonResponse
    {
        $date         = request('date', Carbon::today()->toDateString());
        $contractorId = Auth::id();

        $assignedIds = $this->distributionRepository
            ->getAssignedWorkerIdsForDate($date, $contractorId);

        $available = $this->workerRepository
            ->getActiveWorkers($contractorId)
            ->filter(fn($w) => !in_array($w->id, $assignedIds))
            ->map(fn($w) => ['id' => $w->id, 'name' => $w->name])
            ->values();

        return response()->json(['success' => true, 'data' => $available]);
    }
}
