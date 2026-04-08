<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Advance\StoreAdvanceRequest;
use App\Http\Requests\Advance\UpdateRecoveryMethodRequest;
use App\Models\Advance;
use App\Models\Worker;
use App\Services\AdvanceService;
use App\Repositories\Interfaces\AdvanceRepositoryInterface;
use Illuminate\Http\Request;

class AdvanceController extends Controller
{
    public function __construct(
        private AdvanceService $advanceService,
        private AdvanceRepositoryInterface $advanceRepository,
    ) {}

    /**
     * Display advances for a specific worker
     */
    public function index(Worker $worker)
    {
        $this->authorize('view', $worker);
        
        $filters = request()->only(['period', 'status']);
        $filters['contractor_id'] = auth()->id();
        
        $advances = $this->advanceRepository->findByWorker($worker->id);
        $summary = $this->advanceService->getWorkerAdvanceSummary($worker->id);
        
        return view('contractor.workers.advances.index', compact('worker', 'advances', 'summary'));
    }

    /**
     * Create a new advance (show form)
     */
    public function create(Worker $worker)
    {
        $this->authorize('update', $worker);
        
        return view('contractor.workers.advances.create', compact('worker'));
    }

    /**
     * Store a new advance
     */
    public function store(Worker $worker, StoreAdvanceRequest $request)
    {
        $this->authorize('update', $worker);
        
        $data = $request->validated();
        $data['worker_id'] = $worker->id;
        
        try {
            $advance = $this->advanceService->recordAdvance($data, auth()->id());
            
            // Return JSON for AJAX requests, redirect for form submissions
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تسجيل الدفعة المقدمة بنجاح',
                    'data' => $advance
                ]);
            }
            
            return redirect()->route('contractor.advances.index', $worker)
                ->with('success', 'تم تسجيل الدفعة المقدمة بنجاح');
        } catch (\Exception $e) {
            $message = $e->getMessage();
            
            // Return JSON for AJAX requests, redirect for form submissions
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }
            
            return back()->withErrors(['error' => $message]);
        }
    }

    /**
     * Show advance details
     */
    public function show(Advance $advance)
    {
        $this->authorize('view', $advance);
        
        $advance->load(['worker', 'contractor', 'installments']);
        
        return view('contractor.advances.show', compact('advance'));
    }

    /**
     * Update recovery method for an advance
     */
    public function updateRecoveryMethod(Advance $advance, UpdateRecoveryMethodRequest $request)
    {
        $this->authorize('update', $advance);
        
        try {
            $data = $request->validated();
            
            $details = null;
            if ($data['recovery_method'] === 'installments') {
                $details = [
                    'period' => $data['installment_period'],
                    'count' => $data['installment_count'],
                ];
            }
            
            $this->advanceService->setRecoveryMethod(
                $advance->id,
                $data['recovery_method'],
                $details
            );
            
            return back()->with('success', 'تم تحديث طريقة الاسترجاع بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Record a collection/payment against an advance
     */
    public function recordCollection(Advance $advance, Request $request)
    {
        $this->authorize('update', $advance);
        
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
        ], [
            'amount.required' => 'حقل المبلغ مطلوب',
            'amount.numeric' => 'المبلغ يجب أن يكون رقم',
            'amount.min' => 'المبلغ يجب أن يكون أكبر من الصفر',
        ]);
        
        try {
            $this->advanceService->recordCollection($advance->id, $validated['amount']);
            
            return back()->with('success', 'تم تسجيل الدفعة بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Get all advances for the contractor dashboard
     */
    public function getContractorAdvances(Request $request)
    {
        $filters = $request->only(['period', 'status', 'recovery_method']);
        
        $advances = $this->advanceRepository->getByContractor(auth()->id(), $filters);
        
        return response()->json([
            'success' => true,
            'data' => $advances,
        ]);
    }

    /**
     * Get global summary of all advances
     */
    public function getSummary(Request $request)
    {
        $advances = Advance::forContractor(auth()->id())
            ->withRelations()
            ->get();
        
        $summary = [
            'total_issued' => $advances->sum('amount'),
            'total_collected' => $advances->sum('amount_collected'),
            'total_pending' => $advances->sum('amount_pending'),
            'pending_count' => $advances->where('is_fully_collected', false)->count(),
            'collected_count' => $advances->where('is_fully_collected', true)->count(),
            'this_month_issued' => Advance::forContractor(auth()->id())
                ->thisMonth()
                ->sum('amount'),
            'workers_with_pending' => Advance::forContractor(auth()->id())
                ->pending()
                ->distinct('worker_id')
                ->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }
}
