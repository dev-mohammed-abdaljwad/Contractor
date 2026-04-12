<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $contractors = User::where('role', 'contractor')->with('companies', 'workers')->get();

        $stats = $contractors->map(function($contractor) {
            return [
                'id' => $contractor->id,
                'name' => $contractor->name,
                'phone' => $contractor->phone,
                'companies_count' => $contractor->companies->count(),
                'workers_count' => $contractor->workers->count(),
            ];
        });

        return view('admin.dashboard.index', compact('stats'));
    }

    public function showContractor(User $user)
    {
        if ($user->role !== 'contractor') {
            abort(403);
        }

        $companies = $user->companies()
            ->withCount(['distributions', 'collections', 'payments'])
            ->with('contractor')
            ->get();
        $workers = $user->workers()
            ->withCount('distributions')
            ->get();
        $collections = $user->collections()
            ->with('company')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.contractors.show', compact('user', 'companies', 'workers', 'collections'));
    }
}
