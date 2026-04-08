<?php

namespace App\Http\Controllers\Contractor;

use App\Exceptions\DeductionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Deduction\ReverseDeductionRequest;
use App\Http\Requests\Deduction\StoreDeductionRequest;
use App\Models\Deduction;
use App\Models\Worker;
use App\Services\DeductionService;
use Illuminate\Http\Request;

class DeductionController extends Controller
{
    public function __construct(
        private DeductionService $deductionService,
    ) {}

    /**
     * Display deduction history for a worker.
     */
    public function index(Worker $worker, Request $request)
    {
        $filters = [
            'filter' => $request->query('filter', 'month'),
            'from' => $request->query('from'),
            'to' => $request->query('to'),
            'exclude_reversals' => $request->boolean('exclude_reversals', false),
        ];

        $history = $this->deductionService->getWorkerDeductionHistory($worker->id, $filters);

        return view('contractor.workers.deductions.index', [
            'worker' => $worker,
            'deductions' => $history['deductions'],
            'monthly_total' => $history['monthly_total'],
            'reversal_count' => $history['reversal_count'],
        ]);
    }

    /**
     * Store a new deduction.
     */
    public function store(StoreDeductionRequest $request)
    {
        try {
            $deduction = $this->deductionService->recordDeduction(
                $request->validated(),
                auth()->id()
            );

            $message = 'تم تسجيل الخصم بنجاح بمبلغ ' . number_format($deduction->amount, 2) . ' ج';

            // Return JSON for AJAX requests, redirect for form submissions
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $deduction
                ]);
            }

            session()->flash('success', $message);
            return back();
        } catch (DeductionException $e) {
            $message = $e->getMessage();
            
            // Return JSON for AJAX requests, redirect for form submissions
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }

            session()->flash('error', $message);
            return back()->withInput();
        } catch (\Exception $e) {
            $message = 'حدث خطأ أثناء تسجيل الخصم';
            
            // Return JSON for AJAX requests, redirect for form submissions
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            session()->flash('error', $message);
            return back()->withInput();
        }
    }

    /**
     * Reverse a deduction.
     */
    public function reverse(Deduction $deduction, ReverseDeductionRequest $request)
    {
        try {
            $reversed = $this->deductionService->reverseDeduction(
                $deduction->id,
                $request->input('reversal_reason'),
                auth()->id()
            );

            session()->flash('success', 'تم إلغاء الخصم بنجاح');
            return back();
        } catch (DeductionException $e) {
            session()->flash('error', $e->getMessage());
            return back();
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء إلغاء الخصم');
            return back();
        }
    }

    /**
     * API endpoint to get worker's wage for a specific date (for preview).
     */
    public function getWageForDate($workerId, Request $request)
    {
        $date = $request->query('date');

        if (!$date) {
            return response()->json(['success' => false, 'message' => 'التاريخ مطلوب'], 400);
        }

        try {
            // Verify worker exists and belongs to contractor
            $worker = Worker::where('id', $workerId)
                ->where('contractor_id', auth()->id())
                ->firstOrFail();

            // Find distribution for this worker on this date through pivot table
            $distribution = \App\Models\DailyDistribution::whereHas('workers', 
                fn($q) => $q->where('worker_id', $worker->id)
            )
            ->whereDate('distribution_date', $date)
            ->with('company')
            ->first();

            if (!$distribution) {
                return response()->json([
                    'success' => false,
                    'message' => 'العامل لم يتم توزيعه في هذا اليوم'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'wage' => (float) $distribution->company->daily_wage,
                'company' => $distribution->company->name,
                'distribution_id' => $distribution->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }
}
