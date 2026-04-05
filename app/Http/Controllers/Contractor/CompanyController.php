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
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $allCompanies = Company::where('contractor_id', Auth::id())
            ->with(['distributions', 'collections'])
            ->orderBy('is_active', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        // Enhance company data
        $enhancedCompanies = $allCompanies->map(function ($company) use ($today, $monthStart, $monthEnd) {
            // Today's workers
            $todayDistributions = $company->distributions()
                ->where('distribution_date', $today)
                ->get();
            $company->workers_today = $todayDistributions->count();
            $company->wage_today = $todayDistributions->sum('daily_wage_snapshot');

            // Monthly totals
            $monthDistributions = $company->distributions()
                ->whereBetween('distribution_date', [$monthStart, $monthEnd])
                ->get();
            $company->total_month = $monthDistributions->sum('daily_wage_snapshot');
            $company->days_worked_month = $monthDistributions->pluck('distribution_date')->unique()->count();

            // Payment tracking
            $lastCollection = $company->collections()
                ->where('is_paid', true)
                ->orderByDesc('payment_date')
                ->first();
            $company->last_payment_date = $lastCollection?->payment_date?->format('d M') ?? 'لم يتم';
            
            // Pending amount (amount due)
            $company->amount_due = $company->collections()
                ->where('is_paid', false)
                ->sum('net_amount');

            // Payment status
            $unpaidCollection = $company->collections()
                ->where('is_paid', false)
                ->orderBy('period_end')
                ->first();

            if (!$unpaidCollection) {
                $company->payment_status = 'paid';
                $company->payment_status_label = 'تم التحصيل';
                $company->urgency_days = 0;
                $company->urgency_label = 'آخر دفعة: ' . ($company->last_payment_date ?? 'لم يتم');
            } else {
                $dueDate = Carbon::parse($unpaidCollection->period_end)->addDays(7);
                $daysUntilDue = $dueDate->diffInDays($today, false);
                
                if ($daysUntilDue > 0) {
                    $company->payment_status = 'upcoming';
                    $company->payment_status_label = $dueDate->format('d M');
                    $company->urgency_days = $daysUntilDue;
                    $company->urgency_label = 'موعد الدفع: ' . $dueDate->format('d M');
                } elseif ($daysUntilDue == 0) {
                    $company->payment_status = 'due';
                    $company->payment_status_label = 'يستحق اليوم';
                    $company->urgency_days = 0;
                    $company->urgency_label = 'يستحق اليوم';
                } else {
                    $company->payment_status = 'overdue';
                    $company->payment_status_label = 'متأخر ' . abs($daysUntilDue) . ' يوم';
                    $company->urgency_days = abs($daysUntilDue);
                    $company->urgency_label = 'متأخر ' . abs($daysUntilDue) . ' يوم!';
                }
            }

            // Total workers (all time)
            $company->total_workers = $company->distributions()
                ->pluck('worker_id')
                ->unique()
                ->count();

            return $company;
        });

        // Separate active and inactive
        $activeCompanies = $enhancedCompanies->where('is_active', true)->values();
        $inactiveCompanies = $enhancedCompanies->where('is_active', false)->values();

        // Calculate statistics
        $today_count = $activeCompanies->sum('workers_today');
        $total_due = $activeCompanies->sum('amount_due');
        $overdue_count = $activeCompanies->where('payment_status', 'overdue')->count();
        $active_count = $activeCompanies->count();

        // Group by payment cycle for filters
        $paymentCycles = $activeCompanies->groupBy('payment_cycle')->keys();
        $overdueCompanies = $activeCompanies->where('payment_status', 'overdue');

        return view('contractor.companies.index', compact(
            'activeCompanies',
            'inactiveCompanies',
            'active_count',
            'today_count',
            'total_due',
            'overdue_count',
            'paymentCycles',
            'overdueCompanies'
        ));
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

        // Return JSON for modal edit form
        if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'company' => [
                    'id' => $company->id,
                    'name' => $company->name,
                    'contact_person' => $company->contact_person,
                    'phone' => $company->phone,
                    'daily_wage' => $company->daily_wage,
                    'payment_cycle' => $company->payment_cycle,
                    'weekly_pay_day' => $company->weekly_pay_day,
                    'contract_start_date' => $company->contract_start_date ? $company->contract_start_date->format('Y-m-d') : '',
                    'is_active' => $company->is_active ? 1 : 0,
                    'notes' => $company->notes,
                ]
            ]);
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

