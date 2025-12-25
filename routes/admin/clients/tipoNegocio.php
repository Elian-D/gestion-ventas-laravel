<?php

use App\Http\Controllers\Clients\BusinessTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:configure business types')->group(function () {

    Route::get('tipos-negocios/eliminados', [BusinessTypeController::class, 'eliminadas'])
        ->name('negocios.eliminados');

    Route::resource('tipos-negocios', BusinessTypeController::class)
        ->parameters(['tipos-negocios' => 'negocio'])
        ->names('negocios');

    Route::patch('tipos-negocios/{negocio}/estado', [BusinessTypeController::class, 'toggleEstado'])
        ->name('negocios.toggle');

    Route::patch('tipos-negocios/{id}/restaurar', [BusinessTypeController::class, 'restaurar'])
        ->name('negocios.restaurar');

    Route::delete('tipos-negocios/{id}/borrar', [BusinessTypeController::class, 'borrarDefinitivo'])
        ->name('negocios.borrarDefinitivo');
});
