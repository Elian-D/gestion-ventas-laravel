<?php

use App\Http\Controllers\Inventory\InventoryStockController;
use Illuminate\Support\Facades\Route;


Route::get('stocks/', [InventoryStockController::class, 'index'])
    ->middleware('permission:inventory stocks index')
    ->name('stocks.index');

Route::patch('stocks/{stock}/min-stock', [InventoryStockController::class, 'updateMinStock'])
    ->middleware('permission:inventory stocks update')
    ->name('stocks.update-min-stock');

Route::get('stocks/export', [InventoryStockController::class, 'export'])
    ->middleware('permission:inventory stocks export')
    ->name('stocks.export');

