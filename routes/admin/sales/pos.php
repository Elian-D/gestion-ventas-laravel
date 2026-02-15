<?php

use App\Http\Controllers\Sales\Pos\PosTerminalController;

use Illuminate\Support\Facades\Route;

    use App\Http\Controllers\Sales\Pos\PosCashMovementController;
use App\Http\Controllers\Sales\Pos\PosConfigController;

Route::prefix('pos')->name('pos.')->group(function () {
    
    // CONFIGURACIÓN GLOBAL (Fase 1)
        // Usamos solo edit/update porque es un registro único
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [PosConfigController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:pos config view');
                
            Route::put('/', [PosConfigController::class, 'update'])
                ->name('update')
                ->middleware('permission:pos config update');
        });

    // CRUD Terminales
    Route::prefix('terminals')->name('terminals.')->group(function () {
        Route::get('/', [PosTerminalController::class, 'index'])->name('index');
        // Formularios
        Route::get('/create', [PosTerminalController::class, 'create'])->name('create');
        Route::get('/{pos_terminal}/edit', [PosTerminalController::class, 'edit'])->name('edit');
        Route::post('/', [PosTerminalController::class, 'store'])->name('store');
        Route::put('/{pos_terminal}', [PosTerminalController::class, 'update'])->name('update');
        Route::delete('/{pos_terminal}', [PosTerminalController::class, 'destroy'])->name('destroy');
        
        // Rutas de SoftDeletes (Trait)
        /* 
        Nombres "eliminadas" porque el trait está mal configurado. 
        Y los metodos de restore y force delete van en ese nombre y español porque el trait
        también lo hace así. Si se cambia el nombre del trait, se pueden cambiar estos nombres.
        */ 
        Route::get('/eliminados', [PosTerminalController::class, 'eliminadas'])->name('eliminadas');
        Route::post('/{id}/restore', [PosTerminalController::class, 'restaurar'])->name('restore');
        Route::delete('/{id}/force-delete', [PosTerminalController::class, 'borrarDefinitivo'])->name('force-delete');
    });

    // Gestión de Sesiones (Turnos de Caja)
    Route::prefix('sessions')->name('sessions.')->group(function () {
        Route::get('/', [App\Http\Controllers\Sales\Pos\PosSessionController::class, 'index'])->name('index');
        Route::get('/{pos_session}', [App\Http\Controllers\Sales\Pos\PosSessionController::class, 'show'])->name('show');
        
        // Apertura
        Route::post('/open', [App\Http\Controllers\Sales\Pos\PosSessionController::class, 'store'])->name('store');
        
        // Cierre (Acción de negocio específica)
        Route::patch('/{pos_session}/close', [App\Http\Controllers\Sales\Pos\PosSessionController::class, 'close'])->name('close');
        
        // Edición administrativa
        Route::put('/{pos_session}', [App\Http\Controllers\Sales\Pos\PosSessionController::class, 'update'])->name('update');
    });


    Route::get('/cash-movements', [PosCashMovementController::class, 'index'])->name('cash-movements.index');
    Route::post('/cash-movements', [PosCashMovementController::class, 'store'])->name('cash-movements.store');
});