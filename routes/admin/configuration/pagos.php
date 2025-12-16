<?php

use App\Http\Controllers\Configuration\TipoPagoController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:configure payments')->group(function () {

    Route::get('tipo-pagos/eliminados', [TipoPagoController::class, 'eliminadas'])
        ->name('pagos.eliminados');

    Route::resource('tipo-pagos', TipoPagoController::class)
        ->parameters(['tipo-pagos' => 'tipoPago'])
        ->names('pagos');

    Route::patch('tipo-pagos/{tipoPago}/estado', [TipoPagoController::class, 'toggleEstado'])
        ->name('pagos.toggle');

    Route::patch('tipo-pagos/{id}/restaurar', [TipoPagoController::class, 'restaurar'])
        ->name('pagos.restaurar');

    Route::delete('tipo-pagos/{id}/borrar', [TipoPagoController::class, 'borrarDefinitivo'])
        ->name('pagos.borrarDefinitivo');
});
