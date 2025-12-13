<?php

use App\Http\Controllers\Configuration\TipoDocumentoController;
use Illuminate\Support\Facades\Route;



Route::middleware(['permission:view configuration'])
    ->get('config', function () {
        return view('configuration.index');
    })
    ->name('config.index');


// Rutas de documentos
Route::middleware(['auth', 'permission:manage documents'])->group(function () {
    Route::resource('tipos-documentos', TipoDocumentoController::class);
});

// Rutas de ubicación
/* Route::middleware(['auth', 'permission:manage locations'])->group(function () {
    Route::resource('provincias', ProvinciaController::class);
    Route::resource('municipios', MunicipioController::class);
    Route::resource('sectores', SectorController::class);
}); */