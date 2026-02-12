<?php

use Illuminate\Support\Facades\Route;

// routes/admin/SalesRoutesConfig.php
Route::prefix('sales')->as('sales.')->group(function () {
    
    require __DIR__ . '/sales/sales.php';
    require __DIR__ . '/sales/invoices.php';
    require __DIR__ . '/sales/ncf.php';

    // Aquí podrías agregar en el futuro:
    // require __DIR__ . '/sales/pos.php';
});