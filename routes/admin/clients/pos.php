<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clients\PointOfSaleController;

Route::group(['prefix' => 'puntos-de-venta', 'as' => 'pos.'], function () {
    
    // Listado y AJAX
    Route::get('/', [PointOfSaleController::class, 'index'])
        ->middleware('permission:pos index')
        ->name('index');

    // Creación
    Route::get('/crear', [PointOfSaleController::class, 'create'])
        ->middleware('permission:pos create')
        ->name('create');

    Route::post('/', [PointOfSaleController::class, 'store'])
        ->middleware('permission:pos create')
        ->name('store');

    // Edición
    Route::get('/{pos}/editar', [PointOfSaleController::class, 'edit'])
        ->middleware('permission:pos edit')
        ->name('edit');

    Route::put('/{pos}', [PointOfSaleController::class, 'update'])
        ->middleware('permission:pos edit')
        ->name('update');

    // Acciones Masivas
    Route::post('/bulk-action', [PointOfSaleController::class, 'bulk'])
        ->middleware('permission:pos edit')
        ->name('bulk');

    // Import/Export
    Route::get('/export', [PointOfSaleController::class, 'export'])->name('export');
    Route::get('/import', [PointOfSaleController::class, 'showImportForm'])->name('import.view');
    Route::post('/import', [PointOfSaleController::class, 'import'])->name('import.process');
    Route::get('/import-template', [PointOfSaleController::class, 'downloadTemplate'])->name('template');

    // Eliminación
    Route::delete('/{pos}', [PointOfSaleController::class, 'destroy'])
        ->middleware('permission:pos delete')
        ->name('destroy');

    // Papelera (Trait SoftDeletes)
    Route::get('/eliminados', [PointOfSaleController::class, 'eliminadas'])
        ->middleware('permission:pos restore')
        ->name('eliminados');

    Route::patch('/{id}/restaurar', [PointOfSaleController::class, 'restaurar'])
        ->middleware('permission:pos restore')
        ->name('restore');

    Route::delete('/{id}/forzar-eliminacion', [PointOfSaleController::class, 'borrarDefinitivo'])
        ->middleware('permission:pos delete')
        ->name('borrarDefinitivo');
});