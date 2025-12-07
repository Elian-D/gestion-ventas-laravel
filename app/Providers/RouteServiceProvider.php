<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Rutas públcias
        Route::middleware('web')
        ->group(base_path('routes/web.php'));

        // Rutas administrativas (panel)
        Route::middleware(['web', 'auth'])
        ->prefix('admin')
        ->group(function () {
            foreach (glob(base_path('routes/admin/*.php')) as $routeFile) {
                require $routeFile;
            }
        });
    }
}
