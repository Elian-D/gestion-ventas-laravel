<?php

use Illuminate\Support\Facades\Route;

// routes/admin/InventoryRoutesCondig.php
Route::prefix('accounting')->as('accounting.')->group(function () {
    
    // Solo cargamos el archivo, sin añadir más prefijos aquí 
    // para que no se dupliquen con los del resource
    require __DIR__ . '/accounting/accountingAccounts.php';
    require __DIR__ . '/accounting/journalEntries.php';
    require __DIR__ . '/accounting/documentTypes.php';

});