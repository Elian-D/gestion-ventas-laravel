<?php

use Illuminate\Support\Facades\Route;

// routes/admin/InventoryRoutesCondig.php
Route::prefix('inventory')->as('inventory.')->group(function () {
    
    // Solo cargamos el archivo, sin añadir más prefijos aquí 
    // para que no se dupliquen con los del resource
    require __DIR__ . '/inventory/warehouses.php';
    
    
    require __DIR__ . '/inventory/inventorystock.php';
/*     require __DIR__ . '/inventory/inventory.php'; */
});