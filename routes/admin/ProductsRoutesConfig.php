<?php

use Illuminate\Support\Facades\Route;

// routes/admin/products.php
Route::prefix('products')->as('products.')->group(function () {
    
    // Solo cargamos el archivo, sin añadir más prefijos aquí 
    // para que no se dupliquen con los del resource
    require __DIR__ . '/products/categories.php';
    require __DIR__ . '/products/units.php';

    // Cuando llegues a productos, lo mismo:
    // require __DIR__ . '/products/items.php';
});