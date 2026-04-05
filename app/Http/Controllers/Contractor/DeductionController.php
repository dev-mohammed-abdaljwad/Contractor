<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeductionRequest;
use App\Repositories\Interfaces\DeductionRepositoryInterface;
use App\Repositories\Interfaces\WorkerRepositoryInterface;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Services\DeductionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DeductionController extends Controller
{
    public function __construct(
        private DeductionService $deductionService,
        private DeductionRepositoryInterface $deductionRepository,
        private WorkerRepositoryInterface $workerRepository,
        private CompanyRepositoryInterface $companyRepository,
    ) {}

    public function index()
    {
        $worker = request('worker_id');
        $company = request('company_id');
        $date = request('date');

        $deductions = $this->deductionRepository->findById(Auth::id()) ?? collect();
        
        // This would need to be adjusted to query by contractor
        // For now, get all deductions for the contractor
        $deductions = \App\Models\Deduction::where('contractor_id', Auth::id());

        if ($worker) {
            $deductions = $deductions->where('worker_id', $worker);
        }
        if ($company) {
            $deductions = $deductions->where('company_id', $company);
        }
        if ($date) {
            $deductions = $deductions->where('deduction_date', $date);
        }

        $deductions = $deductions->latest()->paginate(15);

        return view('contractor.deductions.index', compact('deductions', 'worker', 'company', 'date'));
    }

    public function create()
    {
        $workers = $this->workerRepository->getActiveWorkers(Auth::id());
        $companies = $this->companyRepository->getActiveCompanies(Auth::id());
        $today = Carbon::today()->toDateString();

        return view('contractor.deductions.create', compact('workers', 'companies', 'today'));
    }

    public function store(StoreDeductionRequest $request)
    {
        try {
            $this->deductionService->storeDeduction([
                ...$request->validated(),
                'contractor_id' => Auth::id(),
            ]);

            return redirect()->route('deductions.index')
                ->with('success', 'تم إضافة الخصم بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $deduction = $this->deductionRepository->findById($id);
        
        if (!$deduction || $deduction->contractor_id !== Auth::id()) {
            abort(403);
        }

        $this->deductionService->deleteDeduction($id);

        return back()->with('success', 'تم حذف الخصم بنجاح');
    }
}
