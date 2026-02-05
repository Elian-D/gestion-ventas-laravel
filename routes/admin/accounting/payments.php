<?php

use App\Http\Controllers\Accounting\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('payments/export', [PaymentController::class, 'export'])
        ->middleware('permission:export payments')
        ->name('payments.export');

    // Ruta para ver eliminados (SoftDeletes)
    Route::get('payments/eliminados', [PaymentController::class, 'eliminadas'])
        ->middleware('permission:view payments')
        ->name('payments.eliminados');

    // Index y AJAX
    Route::get('payments', [PaymentController::class, 'index'])
        ->middleware('permission:view payments')
        ->name('payments.index');

    // Creación
    Route::get('payments/create', [PaymentController::class, 'create'])
        ->middleware('permission:create payments')
        ->name('payments.create');

    Route::post('payments', [PaymentController::class, 'store'])
        ->middleware('permission:create payments')
        ->name('payments.store');

    Route::get('payments/{payment}/print', [PaymentController::class, 'print'])
        ->middleware('permission:print payment receipts')
        ->name('payments.print');

    // Acción para Anular (Lógica de negocio)
    Route::post('payments/{payment}/cancel', [PaymentController::class, 'cancel'])
        ->middleware('permission:cancel payments')
        ->name('payments.cancel');
});