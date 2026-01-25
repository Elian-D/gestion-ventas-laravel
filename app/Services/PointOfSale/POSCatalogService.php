<?php

namespace App\Services\PointOfSale;

use App\Models\Clients\Client;
use App\Models\Clients\BusinessType;
use App\Models\Geo\State;

class POSCatalogService
{
    public function getForFilters(): array
    {
        $countryId = general_config()?->country_id;

        return [
            'clients'       => Client::select('id', 'name')->orderBy('name')->get(),
            'businessTypes' => BusinessType::select('id', 'name')->orderBy('name')->get(),
            'states'        => $countryId 
                ? State::byCountry($countryId)->select('id', 'name')->orderBy('name')->get()
                : collect(),
        ];
    }

    public function getForForm(): array
    {
        $countryId = general_config()?->country_id;

        return [
            'clients'       => Client::select('id', 'name')->orderBy('name')->get(),
            'businessTypes' => BusinessType::select('id', 'name')->orderBy('name')->get(),
            'states'        => $countryId 
                ? State::byCountry($countryId)->select('id', 'name')->orderBy('name')->get()
                : collect(),
        ];
    }
}