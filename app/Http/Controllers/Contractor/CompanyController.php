<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CompanyController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        private CompanyService $companyService
    ) {}

    public function index(): View
    {
        $data = $this->companyService->getEnhancedCompaniesForContractor(Auth::id());

        return view('contractor.companies.index', [
            'activeCompanies' => $data['activeCompanies'],
            'inactiveCompanies' => $data['inactiveCompanies'],
            'active_count' => $data['stats']['active_count'],
            'today_count' => $data['stats']['today_count'],
            'total_due' => $data['stats']['total_due'],
            'overdue_count' => $data['stats']['overdue_count'],
            'paymentCycles' => $data['paymentCycles'],
            'overdueCompanies' => $data['overdueCompanies'],
        ]);
    }

    public function create(): View
    {
        return view('contractor.companies.create');
    }

    public function store(StoreCompanyRequest $request): JsonResponse|RedirectResponse
    {
        $company = $this->companyService->createCompany([
            ...$request->validated(),
            'contractor_id' => Auth::id(),
        ]);

        return $request->expectsJson() || $request->header('Accept') === 'application/json'
            ? response()->json([
                'success' => true,
                'message' => 'تم إضافة الشركة بنجاح',
                'company' => $company
            ])
            : redirect()->route('contractor.companies.index')
                ->with('success', 'تم إضافة الشركة بنجاح');
    }

    public function show(Company $company): View|JsonResponse
    {
        $this->authorize('view', $company);

        // Get details which loads company with all relations using optimized query
        $details = $this->companyService->getCompanyDetails($company);

        return request()->expectsJson() || request()->header('Accept') === 'application/json'
            ? response()->json(
                $this->companyService->getCompanyAsJson($company)
            )
            : view('contractor.companies.show', [
                'company' => $company,
                'workersToday' => $details['workers_today'],
                'distributionHistory' => $details['distribution_history'],
                'paymentsHistory' => $details['payments_history'],
                'monthlyTotal' => $details['monthly_total'],
                'pendingAmount' => $details['pending_amount'],
            ]);
    }

    public function edit(Company $company): View|JsonResponse
    {
        $this->authorize('update', $company);

        return request()->expectsJson() || request()->header('Accept') === 'application/json'
            ? response()->json([
                'success' => true,
                'company' => $this->companyService->getCompanyAsJson($company)
            ])
            : view('contractor.companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $company);

        $company = $this->companyService->updateCompany($company, $request->validated());

        return $request->expectsJson() || $request->header('Accept') === 'application/json'
            ? response()->json([
                'success' => true,
                'message' => 'تم تحديث الشركة بنجاح',
                'company' => $company
            ])
            : redirect()->route('contractor.companies.show', $company)
                ->with('success', 'تم تحديث الشركة بنجاح');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $this->authorize('delete', $company);

        $this->companyService->deleteCompany($company);

        return redirect()->route('contractor.companies.index')
            ->with('success', 'تم حذف الشركة بنجاح');
    }

}


