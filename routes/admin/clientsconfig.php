<?php

use Illuminate\Support\Facades\Route;

Route::prefix('clients')
    ->as('clients.')
    ->group(function () {
        foreach (glob(__DIR__ . '/clients/*.php') as $file) {
            require $file;
        }
    });
