<?php

use Illuminate\Support\Facades\Route;

// Prefijo de URL: /admin/geography
// Prefijo de nombre: geography.
Route::prefix('geography')
    ->as('geography.')
    ->group(function () {
        foreach (glob(__DIR__ . '/geography/*.php') as $file) {
            require $file;
        }
    });
