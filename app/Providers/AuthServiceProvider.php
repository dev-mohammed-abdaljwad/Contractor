<?php

namespace App\Providers;

use App\Models\Advance;
use App\Policies\AdvancePolicy;
use App\Models\Worker;
use App\Policies\WorkerPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Advance::class => AdvancePolicy::class,
        Worker::class => WorkerPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }

    public function registerPolicies()
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
