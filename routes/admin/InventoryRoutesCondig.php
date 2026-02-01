<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Inventory\InventoryDashboardController;



// routes/admin/InventoryRoutesCondig.php
Route::prefix('inventory')->as('inventory.')->group(function () {
    
    // Solo cargamos el archivo, sin añadir más prefijos aquí 
    // para que no se dupliquen con los del resource
    require __DIR__ . '/inventory/warehouses.php';
    
    
    require __DIR__ . '/inventory/inventorystock.php';
    require __DIR__ . '/inventory/movements.php';

    Route::get('/dashboard', InventoryDashboardController::class)
        ->middleware('permission:view inventory dashboard')
        ->name('dashboard.index');
});