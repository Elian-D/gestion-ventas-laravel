<?php

use App\Http\Controllers\Accounting\AccountingAccountController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:configure accounting account')->group(function () {

    Route::get('accounts/eliminados', [AccountingAccountController::class, 'eliminadas'])
        ->name('accounts.eliminados');

    // Resource principal
    Route::resource('accounts', AccountingAccountController::class)
        ->parameters(['accounts' => 'accounting_account'])
        ->names('accounts');

    Route::patch('accounts/{id}/restaurar', [AccountingAccountController::class, 'restaurar'])
        ->name('accounts.restaurar');

    Route::delete('accounts/{id}/borrar', [AccountingAccountController::class, 'borrarDefinitivo'])
        ->name('accounts.borrarDefinitivo');
});