<?php

use App\Http\Controllers\Sales\SaleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    // Listado y Dashboard de Ventas
    Route::get('/', [SaleController::class, 'index'])
        ->middleware('permission:view sales')
        ->name('index');

    // Creación de Ventas (Formulario y Proceso)
    Route::get('/create', [SaleController::class, 'create'])
        ->middleware('permission:create sales')
        ->name('create');

    Route::post('/', [SaleController::class, 'store'])
        ->middleware('permission:create sales')
        ->name('store');

    // Acción de Anulación (Genera reversión contable e inventario)
    Route::patch('/{sale}/cancel', [SaleController::class, 'cancel'])
        ->middleware('permission:cancel sales')
        ->name('cancel');

    // Exportación de Reportes
    Route::get('/export', [SaleController::class, 'export'])
        ->middleware('permission:view sales')
        ->name('export');
});