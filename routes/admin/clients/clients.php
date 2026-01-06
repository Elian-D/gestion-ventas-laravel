<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clients\ClientController;

Route::group([], function () {
    
    // Listado principal y búsqueda
    Route::get('/', [ClientController::class, 'index'])
        ->middleware('permission:clients index')
        ->name('index');

    // Creación
    Route::get('/crear', [ClientController::class, 'create'])
        ->middleware('permission:clients create')
        ->name('create');

    Route::post('/', [ClientController::class, 'store'])
        ->middleware('permission:clients create')
        ->name('store');

    // Edición
    Route::get('/{client}/editar', [ClientController::class, 'edit'])
        ->middleware('permission:clients edit')
        ->name('edit');

    Route::put('/{client}', [ClientController::class, 'update'])
        ->middleware('permission:clients edit')
        ->name('update');

    // Cambio de estado (Activo/Inactivo)
    Route::patch('/{client}/estado', [ClientController::class, 'toggleEstado'])
        ->middleware('permission:clients edit')
        ->name('toggle');

    // Eliminación (Soft Delete)
    Route::delete('/{client}', [ClientController::class, 'destroy'])
        ->middleware('permission:clients delete')
        ->name('destroy');

    // Gestión de Papelera (Soft Deletes Trait)
    Route::get('/eliminados', [ClientController::class, 'eliminados'])
        ->middleware('permission:clients restore')
        ->name('eliminados');

    Route::patch('/{id}/restaurar', [ClientController::class, 'restore'])
        ->middleware('permission:clients restore')
        ->name('restore');

    Route::delete('/{id}/forzar-eliminacion', [ClientController::class, 'forceDelete'])
        ->middleware('permission:clients delete')
        ->name('force-delete');
});
