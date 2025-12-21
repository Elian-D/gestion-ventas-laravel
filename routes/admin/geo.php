<?php

use App\Models\Geo\Country;
use App\Models\Geo\State;
use Illuminate\Support\Facades\Route;

Route::get('/api/countries/{country}', function (Country $country) {
    return response()->json([
        'phonecode' => $country->phonecode,
        'currency_name' => $country->currency_name,
        'currency' => $country->currency,
        'currency_symbol' => $country->currency_symbol,
        // Tomamos la primera zona horaria por defecto del país
        'timezone' => json_decode($country->timezones, true)[0]['zoneName'] ?? 'UTC',
        'states' => $country->states()->orderBy('name')->get(['id', 'name', 'timezone'])
    ]);
});   