<?php

use App\Http\Controllers\Clients\EquipmentTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:configure equipment types')->group(function () {

    Route::get('equipmentTypes/eliminados', [EquipmentTypeController::class, 'eliminadas'])
        ->name('equipmentTypes.eliminados');

    Route::resource('equipmentTypes', EquipmentTypeController::class)
        ->parameters(['equipmentTypes' => 'equipo'])
        ->names('equipmentTypes');

    Route::patch('equipmentTypes/{equipo}/estado', [EquipmentTypeController::class, 'toggleEstado'])
        ->name('equipmentTypes.toggle');

    Route::patch('equipmentTypes/{id}/restaurar', [EquipmentTypeController::class, 'restaurar'])
        ->name('equipmentTypes.restaurar');

    Route::delete('equipmentTypes/{id}/borrar', [EquipmentTypeController::class, 'borrarDefinitivo'])
        ->name('equipmentTypes.borrarDefinitivo');
});
