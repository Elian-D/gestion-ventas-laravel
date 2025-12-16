<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Geography\MunicipioController;

// Listado
Route::middleware('permission:municipios index')
    ->get('/municipios', [MunicipioController::class, 'index'])
    ->name('municipios.index');

// Crear
Route::middleware('permission:municipios create')
    ->get('/municipios/create', [MunicipioController::class, 'create'])
    ->name('municipios.create');

Route::middleware('permission:municipios create')
    ->post('/municipios', [MunicipioController::class, 'store'])
    ->name('municipios.store');

// Editar
Route::middleware('permission:municipios edit')
    ->get('/municipios/{municipio}/edit', [MunicipioController::class, 'edit'])
    ->name('municipios.edit');

Route::middleware('permission:municipios edit')
    ->put('/municipios/{municipio}', [MunicipioController::class, 'update'])
    ->name('municipios.update');

// Toggle estado
Route::middleware('permission:municipios edit')
    ->patch('/municipios/{municipio}/toggle', [MunicipioController::class, 'toggleEstado'])
    ->name('municipios.toggle');

// Eliminadas / restaurar / borrar definitivo
Route::middleware('permission:municipios delete')
    ->get('/municipios/eliminadas', [MunicipioController::class, 'eliminadas'])
    ->name('municipios.eliminadas');

Route::middleware('permission:municipios delete')
    ->patch('/municipios/{id}/restaurar', [MunicipioController::class, 'restaurar'])
    ->name('municipios.restaurar');

Route::middleware('permission:municipios delete')
    ->delete('/municipios/{id}/borrar-definitivo', [MunicipioController::class, 'borrarDefinitivo'])
    ->name('municipios.borrarDefinitivo');

// Eliminar (soft delete)
Route::middleware('permission:municipios delete')
    ->delete('/municipios/{municipio}', [MunicipioController::class, 'destroy'])
    ->name('municipios.destroy');
