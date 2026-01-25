<?php
// routes/admin/catalogconfig.php

use Illuminate\Support\Facades\Route;

Route::prefix('catalog')
    ->as('catalog.')
    ->group(function () {
        foreach (glob(__DIR__ . '/catalog/*.php') as $file) {
            require $file;
        }
    });