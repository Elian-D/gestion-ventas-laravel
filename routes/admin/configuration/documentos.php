<?php

use App\Http\Controllers\Configuration\TipoDocumentoController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:configure documents')->group(function () {

    Route::get('tipos-documentos/eliminados', [TipoDocumentoController::class, 'eliminadas'])
        ->name('documentos.eliminados');

    Route::resource('tipos-documentos', TipoDocumentoController::class)
        ->parameters(['tipos-documentos' => 'tipoDocumento'])
        ->names('documentos');

    Route::patch('tipos-documentos/{tipoDocumento}/estado', [TipoDocumentoController::class, 'toggleEstado'])
        ->name('documentos.toggle');

    Route::patch('tipos-documentos/{id}/restaurar', [TipoDocumentoController::class, 'restaurar'])
        ->name('documentos.restaurar');

    Route::delete('tipos-documentos/{id}/borrar', [TipoDocumentoController::class, 'borrarDefinitivo'])
        ->name('documentos.borrarDefinitivo');
});
