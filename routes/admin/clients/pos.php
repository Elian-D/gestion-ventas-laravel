<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clients\PointOfSaleController;

// Quitamos el prefix 'pos' de aquí porque el archivo se cargará 
// dentro de un grupo que ya debería manejar la estructura.
Route::group(['as' => 'pos.'], function () {
    
    // 1. RUTAS ESTÁTICAS (Antes del resource para evitar conflictos con {pos})
    Route::get('pos/eliminados', [PointOfSaleController::class, 'eliminadas'])
        ->middleware('permission:pos restore')
        ->name('eliminados');

    Route::get('pos/export', [PointOfSaleController::class, 'export'])
        ->name('export');

    Route::post('pos/bulk-action', [PointOfSaleController::class, 'bulk'])
        ->middleware('permission:pos edit')
        ->name('bulk');

    // 2. RESOURCE CON NOMBRE 'pos'

    // Listado principal y búsqueda
    Route::get('pos/', [PointOfSaleController::class, 'index'])
        ->middleware('permission:pos index')
        ->name('index');

    // Creación
    Route::get('pos/create', [PointOfSaleController::class, 'create'])
        ->middleware('permission:pos create')
        ->name('create');

    Route::post('pos/store', [PointOfSaleController::class, 'store'])
        ->middleware('permission:pos create')
        ->name('store');

    // Edición
    Route::get('pos/{pos}/editar', [PointOfSaleController::class, 'edit'])
        ->middleware('permission:pos edit')
        ->name('edit');

    Route::put('pos/{pos}', [PointOfSaleController::class, 'update'])
        ->middleware('permission:pos edit')
        ->name('update');

    // Eliminación (Soft Delete)
    Route::delete('pos/{pos}', [PointOfSaleController::class, 'destroy'])
        ->middleware('permission:pos delete')
        ->name('destroy');

        // 3. RUTAS CON IDs (Al final)
    Route::patch('pos/{id}/restaurar', [PointOfSaleController::class, 'restaurar'])
        ->middleware('permission:pos restore')
        ->name('restore');

    Route::delete('pos/{id}/forzar-eliminacion', [PointOfSaleController::class, 'borrarDefinitivo'])
        ->middleware('permission:pos delete')
        ->name('borrarDefinitivo');
});