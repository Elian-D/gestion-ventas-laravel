<?php

use App\Http\Controllers\Products\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:configure categories')->group(function () {

    Route::get('categories/eliminados', [CategoryController::class, 'eliminadas'])
        ->name('categories.eliminados');

    Route::resource('categories', CategoryController::class)
        ->parameters(['categories' => 'category'])
        ->names('categories');

    Route::patch('categories/{category}/estado', [CategoryController::class, 'toggleEstado'])
        ->name('categories.toggle');

    Route::patch('categories/{id}/restaurar', [CategoryController::class, 'restaurar'])
        ->name('categories.restaurar');

    Route::delete('categories/{id}/borrar', [CategoryController::class, 'borrarDefinitivo'])
        ->name('categories.borrarDefinitivo');
});
