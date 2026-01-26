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
    // Esto generará admin/clients/pos, admin/clients/pos/create, etc.
    Route::resource('pos', PointOfSaleController::class)
        ->parameters(['pos' => 'pos']) 
        ->names([
            'index'   => 'index',
            'create'  => 'create',
            'edit'    => 'edit',
            'update'  => 'update',
            'destroy' => 'destroy',
        ]);

    // 3. RUTAS CON IDs (Al final)
    Route::patch('pos/{id}/restaurar', [PointOfSaleController::class, 'restaurar'])
        ->middleware('permission:pos restore')
        ->name('restore');

    Route::delete('pos/{id}/forzar-eliminacion', [PointOfSaleController::class, 'borrarDefinitivo'])
        ->middleware('permission:pos delete')
        ->name('borrarDefinitivo');
});