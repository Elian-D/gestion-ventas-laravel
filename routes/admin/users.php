<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'index'])
    ->middleware('permission:users index')
    ->name('users.index');

Route::get('/users', [UserController::class, 'create'])
    ->middleware('permission:users create')
    ->name('users.create');

Route::get('/users', [UserController::class, 'store'])
    ->middleware('permission:users create')
    ->name('users.store');

Route::get('/users', [UserController::class, 'edit'])
    ->middleware('permission:users edit')
    ->name('users.edit');

Route::get('/users', [UserController::class, 'update'])
    ->middleware('permission:users edit')
    ->name('users.update');

Route::get('/users', [UserController::class, 'destroy'])
    ->middleware('permission:users delete')
    ->name('users.destroy');

Route::get('/users/{user}/roles', [UserController::class, 'editRoles'])
    ->middleware('permission:users assign')
    ->name('users.roles');

Route::get('/users', [UserController::class, 'updateRoles'])
    ->middleware('permission:users assign')
    ->name('users.roles');