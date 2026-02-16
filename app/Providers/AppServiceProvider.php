<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Sales\NcfGeneratorInterface;
use App\Services\Sales\Ncf\LocalNcfGenerator;
// --- NUEVOS IMPORTS ---
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NcfGeneratorInterface::class, LocalNcfGenerator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();
        \App\Models\Accounting\Receivable::observe(\App\Observers\ReceivableObserver::class);


        // --- DEFINICIÓN DEL RATE LIMITER PARA EL PIN DEL POS ---
        RateLimiter::for('pos-pin', function (Request $request) {
            return Limit::perMinute(5)->by($request->terminal_id ?: $request->ip())->response(function () {
                return response()->json([
                    'message' => 'Demasiados intentos. Por seguridad, intente de nuevo en 1 minuto.'
                ], 429);
            });
        });
        
        // Solo checkear si estamos en el panel administrativo o POS
        if (app()->runningInConsole()) return;

        view()->composer('admin.pos.*', function ($view) {
            $settings = \App\Models\Sales\Pos\PosSetting::getSettings();
            if (!$settings->default_walkin_customer_id) {
                session()->now('warning', 'El POS no tiene un cliente por defecto configurado.');
            }
        });

    }

    
}