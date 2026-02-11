<?php

use App\Http\Controllers\Sales\Ncf\NcfSequenceController;
use App\Http\Controllers\Sales\Ncf\NcfLogController;
use App\Http\Controllers\Sales\Ncf\NcfTypeController;
use Illuminate\Support\Facades\Route;

// El prefijo 'admin/sales' y el nombre 'sales.' ya vienen del RouteServiceProvider o cargador principal
Route::middleware(['auth', 'permission:manage ncf sequences'])->group(function () {
    
    // Gestión de Secuencias (Lotes)
    Route::prefix('ncf')->name('ncf.')->group(function () {
        
        Route::prefix('sequences')->name('sequences.')->group(function () {
            Route::get('/', [NcfSequenceController::class, 'index'])->name('index');
            Route::post('/', [NcfSequenceController::class, 'store'])->name('store');
            Route::delete('/{sequence}', [NcfSequenceController::class, 'destroy'])->name('destroy');
            
            // Nueva ruta para el umbral de alerta
            Route::patch('/{sequence}/threshold', [NcfSequenceController::class, 'updateThreshold'])
                ->name('update-threshold');

            Route::patch('/{sequence}/extend', [NcfSequenceController::class, 'extend'])
                ->name('extend');
        });

        // Rutas de Logs (Auditoría)
        Route::group(['prefix' => 'logs', 'as' => 'logs.'], function () {
            Route::get('/', [NcfLogController::class, 'index'])->name('index');
            Route::get('/export/excel', [NcfLogController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/txt', [NcfLogController::class, 'exportTxt'])->name('export.txt');
        });

        // Dentro del grupo Route::prefix('ncf')->name('ncf.')

        // Gestión de Tipos (Maestra de Comprobantes)
        Route::group(['prefix' => 'types', 'as' => 'types.'], function () {
            Route::get('/', [NcfTypeController::class, 'index'])->name('index');
            Route::post('/', [NcfTypeController::class, 'store'])->name('store');
            Route::put('/{ncfType}', [NcfTypeController::class, 'update'])->name('update');
            // No incluimos destroy para proteger la integridad de secuencias y logs existentes
        });
    });
});