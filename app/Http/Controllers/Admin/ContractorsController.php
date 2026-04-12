<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Worker;
use App\Models\Payment;
use App\Models\DailyDistribution;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class ContractorsController extends Controller
{
    /**
     * Display the contractors management page.
     */
    public function index(Request $request): View
    {
        // Authorization check
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $query = User::where('role', 'contractor');

        // Apply filters
        $status = $request->query('status', 'all');
        if ($status === 'active') {
            // Active contractors (those with recent distributions)
            $query->whereHas('distributions', function ($q) {
                $q->where('distribution_date', '>=', Carbon::now()->subMonth());
            }, '>', 0);
        } elseif ($status === 'inactive') {
            // Inactive contractors (no recent distributions)
            $query->whereDoesntHave('distributions', function ($q) {
                $q->where('distribution_date', '>=', Carbon::now()->subMonth());
            });
        } elseif ($status === 'pro' || $status === 'enterprise' || $status === 'free') {
            $query->where('plan', $status);
        }

        // Search filter
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->query('sort', 'recent');
        switch ($sort) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'workers':
                $query->withCount('workers')->orderByDesc('workers_count');
                break;
            case 'activity':
                $query->withMax('distributions', 'distribution_date')
                      ->orderByDesc('distributions_max_distribution_date');
                break;
            case 'recent':
            default:
                $query->orderByDesc('created_at');
        }

        $contractors = $query->paginate(12);

        // Get statistics
        $stats = $this->getStatistics();

        // Enrich contractors with additional data
        $contractors->getCollection()->transform(function ($contractor) {
            return [
                'id' => $contractor->id,
                'name' => $contractor->name,
                'email' => $contractor->email,
                'phone' => $contractor->phone,
                'avatar_initials' => $this->getInitials($contractor->name),
                'plan' => $contractor->plan ?? 'free',
                'city' => $contractor->city ?? 'غير محدد',
                'status' => $this->getContractorStatus($contractor),
                'workers_count' => $contractor->workers()->count(),
                'companies_count' => $contractor->companies()->count(),
                'collection_amount' => $this->getContractorCollection($contractor),
                'last_distribution' => $this->getLastDistribution($contractor),
                'created_at' => $contractor->created_at,
            ];
        });

        return view('admin.contractors.index', compact('contractors', 'stats', 'status', 'sort'));
    }

    /**
     * Store a new contractor.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'plan' => 'required|in:free,pro,enterprise',
        ]);

        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => null,
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'role' => 'contractor',
            'plan' => $validated['plan'],
        ]);

        return response()->json([
            'message' => 'تم إضافة المقاول بنجاح',
            'contractor' => $user,
        ]);
    }

    /**
     * Update contractor plan.
     */
    public function updatePlan(Request $request, User $contractor)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($contractor->role !== 'contractor') {
            return response()->json(['error' => 'Invalid contractor'], 404);
        }

        $validated = $request->validate([
            'plan' => 'required|in:free,pro,enterprise',
        ]);

        $contractor->update(['plan' => $validated['plan']]);

        return response()->json([
            'message' => 'تم تحديث خطة الاشتراك',
            'contractor' => $contractor,
        ]);
    }

    /**
     * Update contractor status.
     */
    public function toggleStatus(Request $request, User $contractor)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($contractor->role !== 'contractor') {
            return response()->json(['error' => 'Invalid contractor'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:active,inactive,other',
        ]);

        $contractor->update(['status' => $validated['status']]);

        $statusMessages = [
            'active' => 'تم تفعيل المقاول',
            'inactive' => 'تم إيقاف المقاول',
            'other' => 'تم تغيير حالة المقاول',
        ];

        return response()->json([
            'message' => $statusMessages[$validated['status']] ?? 'تم تحديث حالة المقاول',
            'status' => $validated['status'],
        ]);
    }

    /**
     * Get dashboard statistics.
     */
    private function getStatistics(): array
    {
        return [
            'total_contractors' => User::where('role', 'contractor')->count(),
            'active_contractors' => User::where('role', 'contractor')
                ->where('status', 'active')->count(),
            'inactive_contractors' => User::where('role', 'contractor')
                ->where('status', 'inactive')->count(),
            'pro_contractors' => User::where('role', 'contractor')
                ->where('plan', 'pro')->count(),
            'enterprise_contractors' => User::where('role', 'contractor')
                ->where('plan', 'enterprise')->count(),
        ];
    }

    /**
     * Get contractor status.
     */
    private function getContractorStatus($contractor): string
    {
        return $contractor->status ?? 'active';
    }

    /**
     * Get contractor total collection.
     */
    private function getContractorCollection($contractor): float
    {
        return $contractor->companies()
            ->with('payments')
            ->get()
            ->flatMap->payments
            ->sum('amount') ?? 0;
    }

    /**
     * Get last distribution date for contractor.
     */
    private function getLastDistribution($contractor)
    {
        return $contractor->distributions()
            ->latest('distribution_date')
            ->first();
    }

    /**
     * Get name initials.
     */
    private function getInitials($name): string
    {
        $parts = explode(' ', $name);
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= substr($part, 0, 1);
        }
        return $initials;
    }
}
