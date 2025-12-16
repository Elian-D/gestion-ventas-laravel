<?php

use Illuminate\Support\Facades\Route;

Route::middleware('permission:view configuration')
    ->get('/', fn () => view('configuration.index'))
    ->name('index');
