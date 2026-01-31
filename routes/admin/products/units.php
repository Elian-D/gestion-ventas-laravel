<?php

use App\Http\Controllers\Products\UnitController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:configure units')->group(function () {

    Route::get('units/eliminados', [UnitController::class, 'eliminadas'])
        ->name('units.eliminados');

    Route::resource('units', UnitController::class)
        ->parameters(['units' => 'unit'])
        ->names('units');

    Route::patch('units/{unit}/estado', [UnitController::class, 'toggleEstado'])
        ->name('units.toggle');

    Route::patch('units/{id}/restaurar', [UnitController::class, 'restaurar'])
        ->name('units.restaurar');

    Route::delete('units/{id}/borrar', [UnitController::class, 'borrarDefinitivo'])
        ->name('units.borrarDefinitivo');
});
