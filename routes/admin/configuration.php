<?php

use App\Http\Controllers\Configuration\TipoDocumentoController;
use App\Http\Controllers\Configuration\EstadosClienteController;
use Illuminate\Support\Facades\Route;



Route::middleware(['permission:view configuration'])
    ->get('config', function () {
        return view('configuration.index');
    })
    ->name('config.index');


// Rutas de documentos
Route::middleware(['auth', 'permission:configure documents'])->group(function () {
    Route::resource('tipos-documentos', TipoDocumentoController::class)
    ->parameters([
        'tipos-documentos' => 'tipoDocumento'
    ]);
    
    Route::patch('tipos-documentos/{tipoDocumento}/estado',[TipoDocumentoController::class, 'toggleEstado'])
    ->name('tipos-documentos.toggle');

});

// Agrupa las rutas bajo el prefijo 'admin' y aplica middlewares
Route::middleware(['auth', 'permission:configure client-states'])->prefix('admin')->group(function () {
    
    // Ruta para listar los elementos eliminados (Papelera)
    Route::get('estados-clientes/eliminados', [EstadosClienteController::class, 'eliminadas'])
        ->name('configuration.estados.eliminados');
    

    // Rutas RESTful para la vista principal (Index, Store, Update, Destroy)
    Route::resource('estados-clientes', EstadosClienteController::class)
        ->parameters([
            // Laravel usará el nombre 'estado' en las URLs en lugar de 'estados-clientes'
            'estados-clientes' => 'estado' 
        ])
        // Le añadimos el prefijo de nombre de ruta 'configuration.'
        ->names('configuration.estados'); 

    // Rutas personalizadas (Toggle, Papelera y Restauración/Eliminación definitiva)

    // Ruta para cambiar el estado (Activo/Inactivo)
    Route::patch('estados-clientes/{estado}/estado', [EstadosClienteController::class, 'toggleEstado'])
        ->name('configuration.estados.toggle');


    // Ruta para restaurar un elemento eliminado
    Route::patch('estados-clientes/{id}/restaurar', [EstadosClienteController::class, 'restaurar'])
        ->name('configuration.estados.restaurar');

    // Ruta para eliminar definitivamente un elemento
    Route::delete('estados-clientes/{id}/borrar', [EstadosClienteController::class, 'borrarDefinitivo'])
        ->name('configuration.estados.borrarDefinitivo');

});