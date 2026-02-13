<?php

use App\Http\Controllers\Accounting\ReceivableController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    
    // Grupo de CxC
    Route::prefix('receivables')->name('receivables.')->group(function () {
        
        // Listado y AJAX
        Route::get('/', [ReceivableController::class, 'index'])
            ->middleware('permission:view receivables')
            ->name('index');

        // Papelera
        Route::get('/eliminados', [ReceivableController::class, 'eliminadas'])
            ->name('eliminados');


        // SoftDelete (Borrado de la tabla)
        Route::delete('/{receivable}', [ReceivableController::class, 'destroy'])
            ->middleware('permission:cancel receivables')
            ->name('destroy');

        // Restauración y Borrado Definitivo
        Route::patch('/{id}/restaurar', [ReceivableController::class, 'restaurar'])->name('restaurar');
    });
});