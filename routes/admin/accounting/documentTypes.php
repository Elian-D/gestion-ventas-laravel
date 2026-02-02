<?php

use App\Http\Controllers\Accounting\DocumentTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    
    Route::get('document_types/eliminados', [DocumentTypeController::class, 'eliminadas'])
        ->name('document_types.eliminados');
    // Index y AJAX
    Route::get('document_types', [DocumentTypeController::class, 'index'])
        ->middleware('permission:view document types')
        ->name('document_types.index');

    // CRUD Básico
    Route::get('document_types/create', [DocumentTypeController::class, 'create'])
        ->middleware('permission:create document types')
        ->name('document_types.create');

    Route::post('document_types', [DocumentTypeController::class, 'store'])
        ->middleware('permission:create document types')
        ->name('document_types.store');

    Route::get('document_types/{document_type}/edit', [DocumentTypeController::class, 'edit'])
        ->middleware('permission:edit document types')
        ->name('document_types.edit');

    Route::put('document_types/{document_type}', [DocumentTypeController::class, 'update'])
        ->middleware('permission:edit document types')
        ->name('document_types.update');

    Route::delete('document_types/{document_type}', [DocumentTypeController::class, 'destroy'])
        ->middleware('permission:delete document types')
        ->name('document_types.destroy');


    Route::patch('document_types/{id}/restaurar', [DocumentTypeController::class, 'restaurar'])
        ->name('document_types.restaurar');

    Route::delete('document_types/{id}/borrar', [DocumentTypeController::class, 'borrarDefinitivo'])
        ->name('document_types.borrarDefinitivo');
});