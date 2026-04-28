<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contractor\DashboardController;
use App\Http\Controllers\Contractor\WorkerController;
use App\Http\Controllers\Contractor\CompanyController;
use App\Http\Controllers\Contractor\CollectionController;
use App\Http\Controllers\Contractor\DistributionController;
use App\Http\Controllers\Contractor\DistributionReportController;
use App\Http\Controllers\Contractor\DeductionController;
use App\Http\Controllers\Contractor\AdvanceController;
use App\Http\Controllers\Contractor\OvertimeController;
use App\Http\Controllers\Contractor\SettingsController;
use App\Http\Controllers\Contractor\ProfitController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ContractorsController;

// Public Routes
Route::get('/', function () {
    // Redirect authenticated users to dashboard
    if (auth()->check()) {
        return redirect('/contractor/dashboard')->with('refresh', true);
    }

    return view('welcome');
});

// PWA Routes (must NOT use middleware and must serve with correct headers)
Route::get('/manifest.json', function () {
    $manifest = json_decode(file_get_contents(public_path('manifest.json')), true);
    return response()->json($manifest)
        ->header('Content-Type', 'application/manifest+json')
        ->header('Cache-Control', 'public, max-age=3600');
});

Route::get('/sw.js', function () {
    return response()->file(public_path('sw.js'), [
        'Content-Type' => 'application/javascript',
        'Cache-Control' => 'public, max-age=3600',
        'Service-Worker-Allowed' => '/'
    ]);
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Forgot Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
    Route::post('/forgot-password/verify', [AuthController::class, 'verifyPhone'])->name('password.verify-phone');
    Route::post('/forgot-password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');
});

// Public Routes
Route::get('/request-registration', function () {
    return view('auth.request-registration');
})->name('request-registration');
Route::post('/request-registration', [AuthController::class, 'submitRegistrationRequest'])->name('request-registration.submit');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Contractor Routes (Protected)
Route::middleware(['auth', 'contractor'])->prefix('contractor')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('contractor.dashboard');

    // Workers Management
    Route::prefix('workers')->group(function () {
        Route::get('/', [WorkerController::class, 'index'])->name('contractor.workers.index');
        Route::get('/create', [WorkerController::class, 'create'])->name('contractor.workers.create');
        Route::post('/', [WorkerController::class, 'store'])->name('contractor.workers.store');
        Route::get('/{id}/edit', [WorkerController::class, 'edit'])->name('contractor.workers.edit');
        Route::post('/{id}/payment', [WorkerController::class, 'recordPayment'])->name('contractor.workers.record-payment');
        Route::get('/{id}', [WorkerController::class, 'show'])->name('contractor.workers.show');
        Route::put('/{id}', [WorkerController::class, 'update'])->name('contractor.workers.update');
        Route::delete('/{id}', [WorkerController::class, 'destroy'])->name('contractor.workers.destroy');
    });

    // Overtime Management
    Route::prefix('overtime')->group(function () {
        Route::get('/workers/{worker}', [OvertimeController::class, 'weeklyView'])->name('contractor.overtime.weekly');
        Route::get('/bulk-by-company', [OvertimeController::class, 'bulkByCompanyForm'])->name('contractor.overtime.bulk-by-company-form');
        Route::post('/bulk-by-company', [OvertimeController::class, 'bulkStoreByCompany'])->name('contractor.overtime.bulk-by-company');
        Route::post('/', [OvertimeController::class, 'store'])->name('contractor.overtime.store');
        Route::post('/bulk', [OvertimeController::class, 'bulkStore'])->name('contractor.overtime.bulk');
    });

    // Companies Management
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('contractor.companies.index');
        Route::get('/create', [CompanyController::class, 'create'])->name('contractor.companies.create');
        Route::post('/', [CompanyController::class, 'store'])->name('contractor.companies.store');
        Route::get('/{company}', [CompanyController::class, 'show'])->name('contractor.companies.show');
        Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('contractor.companies.edit');
        Route::put('/{company}', [CompanyController::class, 'update'])->name('contractor.companies.update');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('contractor.companies.destroy');
        Route::post('/{company}/payments', [CompanyController::class, 'recordPayment'])->name('contractor.companies.record-payment');
    });

    // Collections Management
    Route::prefix('collections')->group(function () {
        Route::get('/', [CollectionController::class, 'index'])->name('contractor.collections.index');
        Route::post('/generate', [CollectionController::class, 'generate'])->name('contractor.collections.generate');
        Route::get('/{id}', [CollectionController::class, 'show'])->name('contractor.collections.show');
    });

    // Distributions Management
    Route::prefix('distributions')->group(function () {
        // Reports (before API endpoints to avoid conflicts)
        Route::get('/reports', [DistributionReportController::class, 'index'])->name('contractor.distributions.reports');

        // API Endpoints for real-time calculations (before resourceful routes)
        Route::post('/calculate-earnings', [DistributionController::class, 'calculateEarnings'])->name('contractor.distributions.calculate-earnings');
        Route::get('/assigned-workers', [DistributionController::class, 'getAssignedWorkers'])->name('contractor.distributions.get-assigned-workers');
        Route::get('/available-workers', [DistributionController::class, 'getAvailableWorkers'])->name('contractor.distributions.get-available-workers');
        Route::get('/company-workers', [DistributionController::class, 'getCompanyWorkers'])->name('contractor.distributions.get-company-workers');

        // Resourceful routes
        Route::get('/', [DistributionController::class, 'index'])->name('contractor.distributions.index');
        Route::get('/create', [DistributionController::class, 'create'])->name('contractor.distributions.create');
        Route::post('/', [DistributionController::class, 'store'])->name('contractor.distributions.store');
        Route::get('/{id}', [DistributionController::class, 'show'])->name('contractor.distributions.show');
        Route::get('/{id}/edit', [DistributionController::class, 'edit'])->name('contractor.distributions.edit');
        Route::put('/{id}', [DistributionController::class, 'update'])->name('contractor.distributions.update');
        Route::delete('/{id}', [DistributionController::class, 'destroy'])->name('contractor.distributions.destroy');
    });

    // Deductions Management
    Route::prefix('deductions')->group(function () {
        // API endpoint for wage preview - MUST come before generic routes
        Route::get('/worker/{workerId}/wage-preview', [DeductionController::class, 'getWageForDate'])->name('contractor.deductions.wage-preview');

        // Generic routes after specific ones
        Route::get('/worker/{worker}', [DeductionController::class, 'index'])->name('contractor.deductions.index');
        Route::post('/', [DeductionController::class, 'store'])->name('contractor.deductions.store');
        Route::patch('/{deduction}/reverse', [DeductionController::class, 'reverse'])->name('contractor.deductions.reverse');
    });

    // Advances Management
    Route::prefix('advances')->group(function () {
        // API endpoints for dashboard
        Route::get('/summary', [AdvanceController::class, 'getSummary'])->name('contractor.advances.summary');
        Route::get('/list', [AdvanceController::class, 'getContractorAdvances'])->name('contractor.advances.list');

        // Worker advances
        Route::get('/worker/{worker}', [AdvanceController::class, 'index'])->name('contractor.advances.index');
        Route::post('/worker/{worker}', [AdvanceController::class, 'store'])->name('contractor.advances.store');
        Route::get('/{advance}', [AdvanceController::class, 'show'])->name('contractor.advances.show');
        Route::patch('/{advance}/recovery-method', [AdvanceController::class, 'updateRecoveryMethod'])->name('contractor.advances.update-recovery-method');
        Route::post('/{advance}/record-collection', [AdvanceController::class, 'recordCollection'])->name('contractor.advances.record-collection');
    });

    // Settings Management
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
        Route::patch('/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::patch('/password', [SettingsController::class, 'changePassword'])->name('settings.password');
        Route::patch('/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
        Route::patch('/system', [SettingsController::class, 'updateSystemPreferences'])->name('settings.system');
        Route::delete('/sessions', [SettingsController::class, 'terminateSessions'])->name('settings.sessions');
    });

    // Profit & Wage Reports
    Route::prefix('profit')->group(function () {
        Route::get('/daily',      [ProfitController::class, 'daily'])->name('contractor.profit.daily');
        Route::get('/monthly',    [ProfitController::class, 'monthly'])->name('contractor.profit.monthly');
        Route::get('/calculator', [ProfitController::class, 'calculator'])->name('contractor.profit.calculator');
    });
});

// Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Contractor Management
    Route::prefix('contractors')->group(function () {
        Route::get('/', [ContractorsController::class, 'index'])->name('admin.contractors.index');
        Route::post('/', [ContractorsController::class, 'store'])->name('admin.contractors.store');
        Route::post('/{contractor}/plan', [ContractorsController::class, 'updatePlan'])->name('admin.contractors.update-plan');
        Route::post('/{contractor}/toggle-status', [ContractorsController::class, 'toggleStatus'])->name('admin.contractors.toggle-status');
    });

    // Settings Management
    Route::prefix('settings')->group(function () {
        Route::get('/', [AdminSettingController::class, 'show'])->name('admin.settings.show');
        Route::put('/profile', [AdminSettingController::class, 'updateProfile'])->name('admin.settings.update-profile');
        Route::put('/password', [AdminSettingController::class, 'updatePassword'])->name('admin.settings.update-password');
        Route::put('/preferences', [AdminSettingController::class, 'updatePreferences'])->name('admin.settings.update-preferences');
    });
});
