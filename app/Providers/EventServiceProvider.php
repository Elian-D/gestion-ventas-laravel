<?php

namespace App\Providers;

use App\Events\Sales\Pos\CashMovementRegistered;
use App\Listeners\Sales\Pos\CreateAccountingEntryForMovement;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * El mapeo de eventos a listeners para la aplicación.
     */
    protected $listen = [
        CashMovementRegistered::class => [
            CreateAccountingEntryForMovement::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}