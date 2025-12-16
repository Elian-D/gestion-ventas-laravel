<?php

use App\Http\Controllers\Configuration\TipoDocumentoController;
use App\Http\Controllers\Configuration\EstadosClienteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Configuration\DiaSemanaController;



Route::middleware(['permission:view configuration'])
    ->get('config', function () {
        return view('configuration.index');
    })
    ->name('config.index');


// Agrupa las rutas bajo el prefijo 'admin' y aplica middlewares
Route::middleware(['auth', 'permission:configure documents'])->group(function () {
    
    // Ruta para listar los elementos eliminados (Papelera)
    Route::get('tipos-documentos/eliminados', [TipoDocumentoController::class, 'eliminadas'])
        ->name('configuration.documentos.eliminados');
    

    // Rutas RESTful para la vista principal (Index, Store, Update, Destroy)
    Route::resource('tipos-documentos', TipoDocumentoController::class)
        ->parameters([
            // Laravel usará el nombre 'tipoDocumento' en las URLs en lugar de 'tipos-documentos'
            'tipos-documentos' => 'tipoDocumento' 
        ])
        // Le añadimos el prefijo de nombre de ruta 'configuration.documentos.'
        ->names('configuration.documentos'); 

    // Rutas personalizadas (Toggle, Papelera y Restauración/Eliminación definitiva)

    // Ruta para cambiar el estado (Activo/Inactivo)
    Route::patch('tipos-documentos/{tipoDocumento}/estado', [TipoDocumentoController::class, 'toggleEstado'])
        ->name('configuration.documentos.toggle');


    // Ruta para restaurar un elemento eliminado
    // Nota: Usamos 'id' aquí para poder buscar elementos eliminados con withTrashed() en el controlador
    Route::patch('tipos-documentos/{id}/restaurar', [TipoDocumentoController::class, 'restaurar'])
        ->name('configuration.documentos.restaurar');

    // Ruta para eliminar definitivamente un elemento
    // Nota: Usamos 'id' aquí por la misma razón que en 'restaurar'
    Route::delete('tipos-documentos/{id}/borrar', [TipoDocumentoController::class, 'borrarDefinitivo'])
        ->name('configuration.documentos.borrarDefinitivo');

});

// Agrupa las rutas bajo el prefijo 'admin' y aplica middlewares
Route::middleware(['auth', 'permission:configure client-states'])->group(function () {
    
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

Route::middleware('permission:configure dias semana')->group(function () {

    // Index
    Route::get('dias-semana', [DiaSemanaController::class, 'index'])
        ->name('configuration.dias.index');

    // Toggle estado
    Route::patch('dias-semana/{diaSemana}/estado', [DiaSemanaController::class, 'toggleEstado'])
        ->name('configuration.dias.toggle');
});