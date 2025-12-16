<?php

use App\Http\Controllers\Configuration\DiaSemanaController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:configure dias-semana')->group(function () {

    Route::get('dias-semana', [DiaSemanaController::class, 'index'])
        ->name('dias.index');

    Route::patch('dias-semana/{diaSemana}/estado', [DiaSemanaController::class, 'toggleEstado'])
        ->name('dias.toggle');
});
