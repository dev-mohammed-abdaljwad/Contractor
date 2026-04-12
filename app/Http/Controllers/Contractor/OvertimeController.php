<?php

namespace App\Http\Controllers\Contractor;

use App\Exceptions\OvertimeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Overtime\RecordOvertimeRequest;
use App\Http\Requests\Overtime\BulkOvertimeRequest;
use App\Models\Worker;
use App\Services\OvertimeService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OvertimeController extends Controller
{
    private OvertimeService $overtimeService;

    public function __construct(OvertimeService $overtimeService)
    {
        $this->overtimeService = $overtimeService;
    }

    /**
     * Show weekly overtime view for a worker
     */
    public function weeklyView(Worker $worker, Request $request): View
    {
        // Verify worker belongs to authenticated contractor
        $this->authorize('view', $worker);

        // Get optional week parameter
        $weekStart = null;
        if ($request->has('week')) {
            try {
                $weekStart = Carbon::parse($request->query('week'))->startOfWeek(Carbon::SUNDAY);
            } catch (\Exception $e) {
                // Invalid date format, use current week
                $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);
            }
        }

        $data = $this->overtimeService->getWeeklyView(
            $worker->id,
            auth()->id(),
            $weekStart
        );

        return view('contractor.overtime.weekly', $data);
    }

    /**
     * Record or update overtime for a single distribution
     */
    public function store(RecordOvertimeRequest $request): JsonResponse
    {
        try {
            $distribution = $this->overtimeService->recordOvertime(
                (int) $request->validated('distribution_id'),
                (float) $request->validated('overtime_hours'),
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ ساعات السهر بنجاح ✓',
                'distribution_id' => $distribution->id,
                'overtime_hours' => $distribution->overtime_hours,
                'overtime_amount' => $distribution->overtime_amount,
            ]);
        } catch (OvertimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            logger()->error('Overtime recording error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ البيانات',
            ], 500);
        }
    }

    /**
     * Record overtime for multiple distributions at once
     */
    public function bulkStore(BulkOvertimeRequest $request): JsonResponse
    {
        try {
            $results = $this->overtimeService->recordBulkOvertime(
                $request->validated('entries'),
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ ساعات السهر بنجاح ✓',
                'updated_count' => count($results),
                'distributions' => collect($results)->map(function ($dist) {
                    return [
                        'id' => $dist->id,
                        'overtime_hours' => $dist->overtime_hours,
                        'overtime_amount' => $dist->overtime_amount,
                    ];
                })->all(),
            ]);
        } catch (OvertimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            logger()->error('Bulk overtime recording error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ البيانات',
            ], 500);
        }
    }
}
