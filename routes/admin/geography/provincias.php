<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Geography\ProvinciaController;

// Listado
Route::middleware('permission:provincias index')
    ->get('/provincias', [ProvinciaController::class, 'index'])
    ->name('provincias.index');

// Crear
Route::middleware('permission:provincias create')
    ->get('/provincias/create', [ProvinciaController::class, 'create'])
    ->name('provincias.create');

Route::middleware('permission:provincias create')
    ->post('/provincias', [ProvinciaController::class, 'store'])
    ->name('provincias.store');

// Editar
Route::middleware('permission:provincias edit')
    ->get('/provincias/{provincia}/edit', [ProvinciaController::class, 'edit'])
    ->name('provincias.edit');

Route::middleware('permission:provincias edit')
    ->put('/provincias/{provincia}', [ProvinciaController::class, 'update'])
    ->name('provincias.update');

// Toggle estado
Route::middleware('permission:provincias edit')
    ->patch('/provincias/{provincia}/toggle', [ProvinciaController::class, 'toggleEstado'])
    ->name('provincias.toggle');

// Eliminadas / restaurar / borrar definitivo
Route::middleware('permission:provincias delete')
    ->get('/provincias/eliminadas', [ProvinciaController::class, 'eliminadas'])
    ->name('provincias.eliminadas');

Route::middleware('permission:provincias delete')
    ->patch('/provincias/{id}/restaurar', [ProvinciaController::class, 'restaurar'])
    ->name('provincias.restaurar');

Route::middleware('permission:provincias delete')
    ->delete('/provincias/{id}/borrar-definitivo', [ProvinciaController::class, 'borrarDefinitivo'])
    ->name('provincias.borrarDefinitivo');

// Eliminar (soft delete)
Route::middleware('permission:provincias delete')
    ->delete('/provincias/{provincia}', [ProvinciaController::class, 'destroy'])
    ->name('provincias.destroy');
