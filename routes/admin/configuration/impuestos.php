<?php

use App\Http\Controllers\Configuration\ImpuestoController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'permission:configure taxes'])->group(function () {
    
    Route::get('impuestos/eliminados', [ImpuestoController::class, 'eliminadas'])
        ->name('impuestos.eliminados');
    
    Route::resource('impuestos', ImpuestoController::class)
        ->parameters([
            'impuestos' => 'impuesto' 
        ])
        ->names('impuestos'); 

    Route::patch('impuestos/{impuesto}/estado', [ImpuestoController::class, 'toggleEstado'])
        ->name('impuestos.toggle');

    Route::patch('impuestos/{id}/restaurar', [ImpuestoController::class, 'restaurar'])
        ->name('impuestos.restaurar');

    Route::delete('impuestos/{id}/borrar', [ImpuestoController::class, 'borrarDefinitivo'])
        ->name('impuestos.borrarDefinitivo');
});
