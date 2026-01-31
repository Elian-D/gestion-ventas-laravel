<?php

use App\Http\Controllers\Inventory\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:configure warehouses')->group(function () {

    Route::get('warehouses/eliminados', [WarehouseController::class, 'eliminadas'])
        ->name('warehouses.eliminados');

    Route::resource('warehouses', WarehouseController::class)
        ->parameters(['warehouses' => 'warehouse'])
        ->names('warehouses');

    Route::patch('warehouses/{warehouse}/estado', [WarehouseController::class, 'toggleEstado'])
        ->name('warehouses.toggle');

    Route::patch('warehouses/{id}/restaurar', [WarehouseController::class, 'restaurar'])
        ->name('warehouses.restaurar');

    Route::delete('warehouses/{id}/borrar', [WarehouseController::class, 'borrarDefinitivo'])
        ->name('warehouses.borrarDefinitivo');
});
