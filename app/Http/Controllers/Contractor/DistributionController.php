<?php

namespace App\Http\Controllers\Contractor;

use App\Exceptions\DuplicateDistributionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDistributionRequest;
use App\Http\Requests\UpdateDistributionRequest;
use App\Models\DailyDistribution;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Repositories\Interfaces\WorkerRepositoryInterface;
use App\Services\DistributionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributionController extends Controller
{
    public function __construct(
        private DistributionService $distributionService,
        private CompanyRepositoryInterface $companyRepository,
        private WorkerRepositoryInterface $workerRepository,
    ) {}

    /**
     * Show distributions list for a specific date
     * US-10: View daily distributions
     */
    public function index()
    {
        $contractor = Auth::user();
        $distributions = DailyDistribution::where('contractor_id', $contractor->id)
            ->whereNull('deleted_at')
            ->with('workers', 'company')
            ->orderByDesc('created_at')
            ->get();

        $totalDistributions = $distributions->count();
        $totalWorkers = $distributions->sum(fn($d) => $d->workers->count());
        $totalWages = $distributions->sum('total_amount') ?? 0;
        $editableCount = $distributions->filter(fn($d) => $d->canEdit())->count();

        // Get data for modals
        $companies = $this->companyRepository->getActiveCompanies($contractor->id);
        $workers = $this->workerRepository->getActiveWorkers($contractor->id);

        return view('contractor.distributions.index', compact(
            'distributions',
            'totalDistributions',
            'totalWorkers',
            'totalWages',
            'editableCount',
            'companies',
            'workers'
        ));
    }

    /**
     * Show form for creating new distribution
     * US-10: Distribute workers to a company (create page)
     */
    public function create()
    {
        $contractor = Auth::user();
        $companies = $this->companyRepository->getActiveCompanies($contractor->id);
        $workers = $this->workerRepository->getActiveWorkers($contractor->id);

        return view('contractor.distributions.create', compact('companies', 'workers'));
    }

    /**
     * Store new distribution
     * US-10: Distribute workers to a company
     * US-11: Real-time earnings calculation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'worker_ids' => 'required|array|min:1',
            'worker_ids.*' => 'exists:workers,id',
        ]);

        $contractor = Auth::user();
        $today = now()->toDateString();

        // Check if any worker is already assigned on this date
        $assignedWorkerIds = DailyDistribution::where('distribution_date', $today)
            ->where('contractor_id', $contractor->id)
            ->with('workers')
            ->get()
            ->flatMap(fn($dist) => $dist->workers->pluck('id'))
            ->toArray();

        // Find first worker that's already assigned
        $duplicateWorkerIds = array_intersect($validated['worker_ids'], $assignedWorkerIds);
        
        if (!empty($duplicateWorkerIds)) {
            $duplicateWorker = \App\Models\Worker::find($duplicateWorkerIds[0]);
            return redirect()->route('contractor.distributions.index')
                ->with('error', 'العامل ' . $duplicateWorker->name . ' مسجل بالفعل لشركة أخرى اليوم');
        }

        try {
            // Create distribution
            $distribution = DailyDistribution::create([
                'contractor_id' => $contractor->id,
                'company_id' => $validated['company_id'],
                'total_amount' => count($validated['worker_ids']) * \App\Models\Company::find($validated['company_id'])->daily_wage,
                'distribution_date' => $today,
            ]);

            // Attach workers
            $distribution->workers()->attach($validated['worker_ids']);

            return redirect()->route('contractor.distributions.index')
                ->with('success', 'تم إنشاء التوزيع بنجاح!');
        } catch (\Exception $e) {
            return redirect()->route('contractor.distributions.index')
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Show distribution details
     * US-13: View distribution history and details
     */
    public function show($id)
    {
        $distribution = $this->distributionService->getDistributionHistory($id);

        return view('contractor.distributions.show', compact('distribution'));
    }

    /**
     * Show edit form for distribution
     * US-13: Edit a past distribution
     */
    public function edit(DailyDistribution $distribution)
    {
        $contractor = Auth::user();
        
        // Check if distribution can be edited (within 7 days)
        if (!$distribution->canEdit()) {
            return back()->with('error', 'لا يمكن تعديل التوزيع بعد مرور 7 أيام');
        }

        $workers = $this->workerRepository->getActiveWorkers($contractor->id);

        return view('contractor.distributions.edit', compact('distribution', 'workers'));
    }

    /**
     * Update distribution
     * US-13: Edit a past distribution
     */
    public function update(Request $request, DailyDistribution $distribution)
    {
        $validated = $request->validate([
            'worker_ids' => 'required|array|min:1',
            'worker_ids.*' => 'exists:workers,id',
        ]);

        $oldWorkerIds = $distribution->workers->pluck('id')->toArray();
        $newWorkerIds = $validated['worker_ids'];

        // Sync workers
        $distribution->workers()->sync($newWorkerIds);

        // Update total amount
        $company = $distribution->company;
        $distribution->update([
            'total_amount' => count($newWorkerIds) * $company->daily_wage,
        ]);

        // Log action
        $distribution->actionLogs()->create([
            'contractor_id' => Auth::id(),
            'action' => 'updated',
            'old_data' => ['worker_ids' => $oldWorkerIds],
            'new_data' => ['worker_ids' => $newWorkerIds],
        ]);

        return redirect()->route('contractor.distributions.show', $distribution)
            ->with('success', 'تم تحديث التوزيع بنجاح!');
    }

    /**
     * Cancel a distribution
     * US-13: Cancel a past distribution with logging
     */
    public function destroy(DailyDistribution $distribution)
    {
        // Check if distribution can be cancelled
        if (!$distribution->canEdit()) {
            return back()->with('error', 'لا يمكن إلغاء التوزيع بعد مرور 7 أيام');
        }

        // Log action before deletion
        $distribution->actionLogs()->create([
            'contractor_id' => Auth::id(),
            'action' => 'cancelled',
            'old_data' => null,
            'new_data' => ['cancelled_at' => now()],
        ]);

        $distribution->delete();

        return redirect()->route('contractor.distributions.index')
            ->with('success', 'تم إلغاء التوزيع بنجاح!');
    }

    /**
     * API endpoint for real-time earnings calculation
     * US-11: See real-time earnings summary before confirming
     */
    public function calculateEarnings(): JsonResponse
    {
        $companyId = request('company_id');
        $workerIds = request('worker_ids', []);
        $date = request('date', Carbon::today()->toDateString());

        try {
            $earnings = $this->distributionService->calculateRealTimeEarnings(
                $companyId,
                $workerIds,
                $date
            );

            return response()->json([
                'success' => true,
                'data' => $earnings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get assigned workers for a specific date
     * US-10: Show already assigned workers
     */
    public function getAssignedWorkers(): JsonResponse
    {
        $date = request('date', Carbon::today()->toDateString());

        // Get all distributions for this date and flatten their workers
        $assigned = DailyDistribution::where('distribution_date', $date)
            ->where('contractor_id', Auth::id())
            ->with('workers', 'company')
            ->get()
            ->flatMap(function ($distribution) {
                return $distribution->workers->map(function ($worker) use ($distribution) {
                    return [
                        'id' => $distribution->id,
                        'worker_id' => $worker->id,
                        'worker_name' => $worker->name,
                        'company_id' => $distribution->company_id,
                        'company_name' => $distribution->company->name,
                    ];
                });
            });

        return response()->json([
            'success' => true,
            'data' => $assigned,
        ]);
    }

    /**
     * Get available workers for a specific date (not yet assigned)
     */
    public function getAvailableWorkers(): JsonResponse
    {
        $date = request('date', Carbon::today()->toDateString());
        $contractor = Auth::user();

        // Get all workers
        $allWorkers = $this->workerRepository->getActiveWorkers($contractor->id);

        // Get assigned worker IDs for this date
        $assignedWorkerIds = DailyDistribution::where('distribution_date', $date)
            ->where('contractor_id', $contractor->id)
            ->with('workers')
            ->get()
            ->flatMap(fn($dist) => $dist->workers->pluck('id'))
            ->toArray();

        // Filter out assigned workers
        $availableWorkers = $allWorkers->filter(fn($worker) => !in_array($worker->id, $assignedWorkerIds))
            ->map(fn($worker) => [
                'id' => $worker->id,
                'name' => $worker->name,
            ])
            ->values();

        return response()->json([
            'success' => true,
            'data' => $availableWorkers,
        ]);
    }
}
