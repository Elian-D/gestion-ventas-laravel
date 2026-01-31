<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\ProductController;

Route::group([], function () {
    
    // Listado principal y búsqueda (Pipeline Filters + AJAX)
    Route::get('/', [ProductController::class, 'index'])
        ->middleware('permission:view products')
        ->name('index');

    // Creación
    Route::get('/crear', [ProductController::class, 'create'])
        ->middleware('permission:create products')
        ->name('create');

    Route::post('/', [ProductController::class, 'store'])
        ->middleware('permission:create products')
        ->name('store');

    // Edición
    Route::get('/{product}/editar', [ProductController::class, 'edit'])
        ->middleware('permission:edit products')
        ->name('edit');

    Route::put('/{product}', [ProductController::class, 'update'])
        ->middleware('permission:edit products')
        ->name('update');

    // Acciones Masivas
    Route::post('/bulk-action', [ProductController::class, 'bulk'])
        ->middleware('permission:edit products')
        ->name('bulk');

    // Eliminación (Soft Delete)
    Route::delete('/{product}', [ProductController::class, 'destroy'])
        ->middleware('permission:delete products')
        ->name('destroy');

    // Gestión de Papelera (Trait SoftDeletes)
    Route::get('/eliminados', [ProductController::class, 'eliminadas'])
        ->middleware('permission:restore products')
        ->name('eliminados');

    Route::patch('/{id}/restaurar', [ProductController::class, 'restaurar'])
        ->middleware('permission:restore products')
        ->name('restore');

    Route::delete('/{id}/forzar-eliminacion', [ProductController::class, 'borrarDefinitivo'])
        ->middleware('permission:delete products')
        ->name('borrarDefinitivo');
});