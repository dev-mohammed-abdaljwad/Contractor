<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdvanceRequest;
use App\Services\AdvanceService;
use App\Repositories\Interfaces\AdvanceRepositoryInterface;
use App\Repositories\Interfaces\WorkerRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AdvanceController extends Controller
{
    public function __construct(
        private AdvanceService $advanceService,
        private AdvanceRepositoryInterface $advanceRepository,
        private WorkerRepositoryInterface $workerRepository,
    ) {}

    public function index()
    {
        $advances = \App\Models\Advance::where('contractor_id', Auth::id())
            ->with('worker')
            ->latest()
            ->paginate(15);

        return view('contractor.advances.index', compact('advances'));
    }

    public function store(StoreAdvanceRequest $request)
    {
        $this->advanceService->storeAdvance([
            ...$request->validated(),
            'contractor_id' => Auth::id(),
        ]);

        return redirect()->route('advances.index')
            ->with('success', 'تم إضافة المتقدم بنجاح');
    }

    public function settle($id)
    {
        $advance = $this->advanceRepository->findById($id);
        
        if (!$advance || $advance->contractor_id !== Auth::id()) {
            abort(403);
        }

        $this->advanceService->settleAdvance($id);

        return back()->with('success', 'تم تسوية المتقدم بنجاح');
    }

    public function destroy($id)
    {
        $advance = $this->advanceRepository->findById($id);
        
        if (!$advance || $advance->contractor_id !== Auth::id()) {
            abort(403);
        }

        $advance->delete();

        return back()->with('success', 'تم حذف المتقدم بنجاح');
    }
}
