<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contractor\DashboardController;
use App\Http\Controllers\Contractor\WorkerController;
use App\Http\Controllers\Contractor\CompanyController;
use App\Http\Controllers\Contractor\CollectionController;
use App\Http\Controllers\Contractor\DistributionController;
use App\Http\Controllers\Contractor\DeductionController;
use App\Http\Controllers\Contractor\AdvanceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

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
        Route::get('/{id}', [WorkerController::class, 'show'])->name('contractor.workers.show');
        Route::put('/{id}', [WorkerController::class, 'update'])->name('contractor.workers.update');
        Route::delete('/{id}', [WorkerController::class, 'destroy'])->name('contractor.workers.destroy');
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
    });

    // Collections Management
    Route::prefix('collections')->group(function () {
        Route::get('/', [CollectionController::class, 'index'])->name('contractor.collections.index');
        Route::post('/generate', [CollectionController::class, 'generate'])->name('contractor.collections.generate');
        Route::get('/{id}', [CollectionController::class, 'show'])->name('contractor.collections.show');
    });

    // Distributions Management
    Route::prefix('distributions')->group(function () {
        // API Endpoints for real-time calculations (before resourceful routes)
        Route::post('/calculate-earnings', [DistributionController::class, 'calculateEarnings'])->name('contractor.distributions.calculate-earnings');
        Route::get('/assigned-workers', [DistributionController::class, 'getAssignedWorkers'])->name('contractor.distributions.get-assigned-workers');
        Route::get('/available-workers', [DistributionController::class, 'getAvailableWorkers'])->name('contractor.distributions.get-available-workers');
        
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
        Route::post('/', [DeductionController::class, 'store'])->name('contractor.deductions.store');
    });

    // Advances Management
    Route::prefix('advances')->group(function () {
        Route::post('/', [AdvanceController::class, 'store'])->name('contractor.advances.store');
    });
});

// Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Contractor Management
    Route::get('/contractors', [AdminController::class, 'index'])->name('admin.contractors.index');
    Route::get('/contractors/{user}', [AdminController::class, 'showContractor'])->name('admin.contractors.show');
});
