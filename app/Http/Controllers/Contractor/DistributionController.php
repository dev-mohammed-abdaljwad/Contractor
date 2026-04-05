<?php

namespace App\Http\Controllers\Contractor;

use App\Exceptions\DuplicateDistributionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDistributionRequest;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Repositories\Interfaces\WorkerRepositoryInterface;
use App\Services\DistributionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DistributionController extends Controller
{
    public function __construct(
        private DistributionService $distributionService,
        private CompanyRepositoryInterface $companyRepository,
        private WorkerRepositoryInterface $workerRepository,
    ) {}

    public function index()
    {
        $date = request('date', Carbon::today()->toDateString());
        $summary = $this->distributionService->getDailySummary(Auth::id(), $date);

        return view('contractor.distributions.index', compact('summary', 'date'));
    }

    public function create()
    {
        $companies = $this->companyRepository->getActiveCompanies(Auth::id());
        $workers = $this->workerRepository->getActiveWorkers(Auth::id());

        return view('contractor.distributions.create', compact('companies', 'workers'));
    }

    public function store(StoreDistributionRequest $request)
    {
        try {
            $this->distributionService->distributeWorkers(
                Auth::id(),
                $request->input('distribution_date'),
                $request->input('assignments')
            );

            return redirect()->route('distributions.index', [
                'date' => $request->input('distribution_date')
            ])->with('success', 'تم توزيع العمال بنجاح');
        } catch (DuplicateDistributionException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $this->distributionService->deleteDistribution($id);

        return back()->with('success', 'تم حذف التوزيع بنجاح');
    }
}
