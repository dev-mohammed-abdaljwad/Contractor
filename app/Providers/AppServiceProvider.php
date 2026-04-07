<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repository Bindings
        $this->app->bind(\App\Repositories\Interfaces\CompanyRepositoryInterface::class, \App\Repositories\CompanyRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\WorkerRepositoryInterface::class, \App\Repositories\WorkerRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\DistributionRepositoryInterface::class, \App\Repositories\DistributionRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\DeductionRepositoryInterface::class, \App\Repositories\DeductionRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\AdvanceRepositoryInterface::class, \App\Repositories\AdvanceRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\CollectionRepositoryInterface::class, \App\Repositories\CollectionRepository::class);

        // Service Bindings
        $this->app->singleton(\App\Services\CompanyService::class, function ($app) {
            return new \App\Services\CompanyService(
                $app->make(\App\Repositories\CompanyRepository::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Telescope will be registered if available
    }
}
