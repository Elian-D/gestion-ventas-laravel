<?php

use App\Http\Controllers\Configuration\EstadosClienteController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:configure client-states')->group(function () {

    Route::get('estados-clientes/eliminados', [EstadosClienteController::class, 'eliminadas'])
        ->name('estados.eliminados');

    Route::resource('estados-clientes', EstadosClienteController::class)
        ->parameters(['estados-clientes' => 'estado'])
        ->names('estados');

    Route::patch('estados-clientes/{estado}/estado', [EstadosClienteController::class, 'toggleEstado'])
        ->name('estados.toggle');

    Route::patch('estados-clientes/{id}/restaurar', [EstadosClienteController::class, 'restaurar'])
        ->name('estados.restaurar');

    Route::delete('estados-clientes/{id}/borrar', [EstadosClienteController::class, 'borrarDefinitivo'])
        ->name('estados.borrarDefinitivo');
});
