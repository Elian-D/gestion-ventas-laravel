<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clients\EquipmentController;

Route::group(['as' => 'equipment.'], function () {

    // Papelera
    Route::get('equipments/eliminados', [EquipmentController::class, 'eliminadas'])
        ->middleware('permission:equipment restore')
        ->name('eliminados');

    // Exportar
    Route::get('equipments/export', [EquipmentController::class, 'export'])
        ->name('export');

    // Acciones masivas
    Route::post('equipments/bulk-action', [EquipmentController::class, 'bulk'])
        ->middleware('permission:equipment edit')
        ->name('bulk');

    // Resource principal
    Route::resource('equipments', EquipmentController::class)
        ->parameters(['equipments' => 'equipment'])
        ->names([
            'index'   => 'index',
            'create'  => 'create',
            'edit'    => 'edit',
            'update'  => 'update',
            'destroy' => 'destroy',
        ]);

    // Restaurar
    Route::patch('equipments/{id}/restaurar', [EquipmentController::class, 'restaurar'])
        ->middleware('permission:equipment restore')
        ->name('restore');

    // Eliminación definitiva
    Route::delete('equipments/{id}/forzar-eliminacion', [EquipmentController::class, 'borrarDefinitivo'])
        ->middleware('permission:equipment delete')
        ->name('borrarDefinitivo');
});
