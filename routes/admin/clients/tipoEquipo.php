<?php

use App\Http\Controllers\Clients\EquipmentTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:configure equipment types')->group(function () {

    Route::get('tipos-equipos/eliminados', [EquipmentTypeController::class, 'eliminadas'])
        ->name('equipos.eliminados');

    Route::resource('tipos-equipos', EquipmentTypeController::class)
        ->parameters(['tipos-equipos' => 'equipo'])
        ->names('equipos');

    Route::patch('tipos-equipos/{equipo}/estado', [EquipmentTypeController::class, 'toggleEstado'])
        ->name('equipos.toggle');

    Route::patch('tipos-equipos/{id}/restaurar', [EquipmentTypeController::class, 'restaurar'])
        ->name('equipos.restaurar');

    Route::delete('tipos-equipos/{id}/borrar', [EquipmentTypeController::class, 'borrarDefinitivo'])
        ->name('equipos.borrarDefinitivo');
});
