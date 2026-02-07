<?php

use Illuminate\Support\Facades\Route;

// routes/admin/SalesRoutesConfig.php
Route::prefix('sales')->as('sales.')->group(function () {
    
    // Cargamos el archivo de gestión de ventas
    require __DIR__ . '/sales/sales.php';
    
    // Aquí podrías agregar en el futuro:
    // require __DIR__ . '/sales/pos.php';
    // require __DIR__ . '/sales/quotations.php';
});