<?php

use App\Http\Controllers\Configuration\ConfiguracionGeneralController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:configure general data')->group(function () {

    Route::get('general', [ConfiguracionGeneralController::class, 'edit'])
        ->name('general.edit');

    Route::put('general', [ConfiguracionGeneralController::class, 'update'])
        ->name('general.update');

});
