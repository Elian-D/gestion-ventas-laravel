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


    // Listado principal y búsqueda
    Route::get('equipments/', [EquipmentController::class, 'index'])
        ->middleware('permission:equipment index')
        ->name('index');

    // Creación
    Route::get('equipments/create', [EquipmentController::class, 'create'])
        ->middleware('permission:equipment create')
        ->name('create');

    Route::post('equipments/store', [EquipmentController::class, 'store'])
        ->middleware('permission:equipment create')
        ->name('store');

    // Edición
    Route::get('equipments/{equipment}/editar', [EquipmentController::class, 'edit'])
        ->middleware('permission:equipment edit')
        ->name('edit');

    Route::put('equipments/{equipment}', [EquipmentController::class, 'update'])
        ->middleware('permission:equipment edit')
        ->name('update');

    // Eliminación (Soft Delete)
    Route::delete('equipments/{id}', [EquipmentController::class, 'destroy'])
        ->middleware('permission:equipment delete')
        ->name('destroy');


    // Restaurar
    Route::patch('equipments/{id}/restaurar', [EquipmentController::class, 'restaurar'])
        ->middleware('permission:equipment restore')
        ->name('restore');

    // Eliminación definitiva
    Route::delete('equipments/{id}/forzar-eliminacion', [EquipmentController::class, 'borrarDefinitivo'])
        ->middleware('permission:equipment delete')
        ->name('borrarDefinitivo');
});
