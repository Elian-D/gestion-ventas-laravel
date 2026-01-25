<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;

Route::get('/states', [CatalogController::class, 'states'])->name('states');
Route::get('/tax-types', [CatalogController::class, 'taxTypes'])->name('tax-types');
Route::get('/client-status', [CatalogController::class, 'clientStatus'])->name('client-status');