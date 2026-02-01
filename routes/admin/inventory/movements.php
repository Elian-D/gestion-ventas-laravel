<?php

use App\Http\Controllers\Inventory\InventoryMovementController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    // Visualización del historial y Dashboard
    Route::get('movements', [InventoryMovementController::class, 'index'])
        ->middleware('permission:view inventory movements')
        ->name('movements.index');

    // Registro de ajustes manuales (Subir/Bajar stock)
    Route::post('movements', [InventoryMovementController::class, 'store'])
        ->middleware('permission:create inventory adjustments')
        ->name('movements.store');

    // Exportación de auditoría
    Route::get('movements/export', [InventoryMovementController::class, 'export'])
        ->middleware('permission:view inventory movements')
        ->name('movements.export');

});