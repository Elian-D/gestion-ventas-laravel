<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Geography\SectorController;

// Listado
Route::middleware('permission:sectores index')
    ->get('/sectores', [SectorController::class, 'index'])
    ->name('sectores.index');

// Crear
Route::middleware('permission:sectores create')
    ->get('/sectores/create', [SectorController::class, 'create'])
    ->name('sectores.create');

Route::middleware('permission:sectores create')
    ->post('/sectores', [SectorController::class, 'store'])
    ->name('sectores.store');

// Editar
Route::middleware('permission:sectores edit')
    ->get('/sectores/{sector}/edit', [SectorController::class, 'edit'])
    ->name('sectores.edit');

Route::middleware('permission:sectores edit')
    ->put('/sectores/{sector}', [SectorController::class, 'update'])
    ->name('sectores.update');

// Toggle estado
Route::middleware('permission:sectores edit')
    ->patch('/sectores/{sector}/toggle', [SectorController::class, 'toggleEstado'])
    ->name('sectores.toggle');

// Eliminadas / restaurar / borrar definitivo
Route::middleware('permission:sectores delete')
    ->get('/sectores/eliminadas', [SectorController::class, 'eliminadas'])
    ->name('sectores.eliminadas');

Route::middleware('permission:sectores delete')
    ->patch('/sectores/{id}/restaurar', [SectorController::class, 'restaurar'])
    ->name('sectores.restaurar');

Route::middleware('permission:sectores delete')
    ->delete('/sectores/{id}/borrar-definitivo', [SectorController::class, 'borrarDefinitivo'])
    ->name('sectores.borrarDefinitivo');

// Eliminar (soft delete)
Route::middleware('permission:sectores delete')
    ->delete('/sectores/{sector}', [SectorController::class, 'destroy'])
    ->name('sectores.destroy');
