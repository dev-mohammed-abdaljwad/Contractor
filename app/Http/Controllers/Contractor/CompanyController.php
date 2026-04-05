<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::where('contractor_id', Auth::id())
            ->with(['distributions', 'collections'])
            ->orderBy('is_active', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $companies = $companies->map(function ($company) {
            $company->total_workers = $company->distributions()
                ->select('worker_id')
                ->distinct()
                ->count();
            $company->pending_amount = $company->collections()
                ->where('is_paid', false)
                ->sum('net_amount');
            return $company;
        });

        return view('contractor.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('contractor.companies.create');
    }

    public function store(StoreCompanyRequest $request)
    {
        $company = Company::create([
            ...$request->validated(),
            'contractor_id' => Auth::id(),
        ]);

        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الشركة بنجاح',
                'company' => $company
            ]);
        }

        return redirect()->route('contractor.companies.index')
            ->with('success', 'تم إضافة الشركة بنجاح');
    }

    public function show(Company $company)
    {
        if ($company->contractor_id !== Auth::id()) {
            abort(403);
        }

        // Eager load distributions with workers
        $company->load('distributions.worker');

        // Calculate statistics
        $company->total_workers = $company->distributions()
            ->select('worker_id')
            ->distinct()
            ->count();

        $company->pending_amount = $company->collections()
            ->where('is_paid', false)
            ->sum('net_amount');

        // Return JSON for modal edit form
        if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
            return response()->json([
                'id' => $company->id,
                'name' => $company->name,
                'contact_person' => $company->contact_person,
                'phone' => $company->phone,
                'daily_wage' => $company->daily_wage,
                'payment_cycle' => $company->payment_cycle,
                'weekly_pay_day' => $company->weekly_pay_day,
                'contract_start_date' => $company->contract_start_date->format('Y-m-d'),
                'is_active' => $company->is_active,
                'notes' => $company->notes,
            ]);
        }

        return view('contractor.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        if ($company->contractor_id !== Auth::id()) {
            abort(403);
        }

        return view('contractor.companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        if ($company->contractor_id !== Auth::id()) {
            abort(403);
        }

        $company->update($request->validated());

        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الشركة بنجاح',
                'company' => $company
            ]);
        }

        return redirect()->route('contractor.companies.show', $company)
            ->with('success', 'تم تحديث الشركة بنجاح');
    }

    public function destroy(Company $company)
    {
        if ($company->contractor_id !== Auth::id()) {
            abort(403);
        }

        $company->delete();

        return redirect()->route('contractor.companies.index')
            ->with('success', 'تم حذف الشركة بنجاح');
    }
}

