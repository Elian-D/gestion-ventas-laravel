<?php

use App\Http\Controllers\Accounting\ReceivableController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    
    // Ruta para ver eliminados (SoftDeletes)
    Route::get('receivables/eliminados', [ReceivableController::class, 'eliminadas'])
        ->name('receivables.eliminados');

    // Index y AJAX
    Route::get('receivables', [ReceivableController::class, 'index'])
        ->middleware('permission:view receivables')
        ->name('receivables.index');

    // CRUD y Acciones de Estado
    Route::get('receivables/create', [ReceivableController::class, 'create'])
        ->middleware('permission:create receivables')
        ->name('receivables.create');

    Route::post('receivables', [ReceivableController::class, 'store'])
        ->middleware('permission:create receivables')
        ->name('receivables.store');

    Route::get('receivables/{receivable}/edit', [ReceivableController::class, 'edit'])
        ->middleware('permission:edit receivables')
        ->name('receivables.edit');

    Route::put('receivables/{receivable}', [ReceivableController::class, 'update'])
        ->middleware('permission:edit receivables')
        ->name('receivables.update');

    // Registro de abonos/pagos
    Route::post('receivables/{receivable}/payment', [ReceivableController::class, 'registerPayment'])
        ->middleware('permission:edit receivables') // O el permiso que definas para cobrar
        ->name('receivables.payment');

    // Acción para Anular (Lógica de negocio)
    Route::post('receivables/{receivable}/cancel', [ReceivableController::class, 'cancel'])
        ->middleware('permission:cancel receivables')
        ->name('receivables.cancel');

    Route::delete('receivables/{receivable}', [ReceivableController::class, 'destroy'])
        ->middleware('permission:cancel receivables')
        ->name('receivables.destroy');

    // Restauración de SoftDeletes
    Route::patch('receivables/{id}/restaurar', [ReceivableController::class, 'restaurar'])
        ->name('receivables.restaurar');

    Route::delete('receivables/{id}/borrar', [ReceivableController::class, 'borrarDefinitivo'])
        ->name('receivables.borrarDefinitivo');
});