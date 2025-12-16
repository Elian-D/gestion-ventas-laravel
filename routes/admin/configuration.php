<?php

use Illuminate\Support\Facades\Route;

Route::prefix('config')
    ->as('configuration.')
    ->group(function () {
        foreach (glob(__DIR__ . '/configuration/*.php') as $file) {
            require $file;
        }
    });
