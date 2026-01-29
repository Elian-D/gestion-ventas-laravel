<?php

use App\Http\Controllers\Clients\BusinessTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:configure business types')->group(function () {

    Route::get('businessTypes/eliminados', [BusinessTypeController::class, 'eliminadas'])
        ->name('businessTypes.eliminados');

    Route::resource('businessTypes', BusinessTypeController::class)
        ->parameters(['businessTypes' => 'negocio'])
        ->names('businessTypes');

    Route::patch('businessTypes/{negocio}/estado', [BusinessTypeController::class, 'toggleEstado'])
        ->name('businessTypes.toggle');

    Route::patch('businessTypes/{id}/restaurar', [BusinessTypeController::class, 'restaurar'])
        ->name('businessTypes.restaurar');

    Route::delete('businessTypes/{id}/borrar', [BusinessTypeController::class, 'borrarDefinitivo'])
        ->name('businessTypes.borrarDefinitivo');
});
