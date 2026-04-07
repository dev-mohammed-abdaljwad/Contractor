<?php

use App\Providers\AppServiceProvider;

$providers = [
    AppServiceProvider::class,
];

// Only register Telescope in local development
if (env('APP_ENV') === 'local' || env('APP_DEBUG') === true) {
    $providers[] = \App\Providers\TelescopeServiceProvider::class;
}

return $providers;
