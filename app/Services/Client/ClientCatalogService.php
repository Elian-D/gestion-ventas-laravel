<?php

namespace App\Services\Client;

use App\Models\Geo\State;
use App\Models\Configuration\EstadosCliente;
use App\Models\Configuration\TaxIdentifierType;

class ClientCatalogService
{
    public function getForFilters(): array
    {
        $countryId = general_config()?->country_id;

        return [
            'states' => $countryId 
                ? State::byCountry($countryId)->select('id', 'name')->orderBy('name')->get()
                : collect(),
                
            'taxIdentifierTypes' => $countryId 
                ? TaxIdentifierType::byCountry($countryId)->select('id', 'code', 'name')->orderBy('name')->get()
                : collect(),
                
            'estadosClientes' => EstadosCliente::select('id', 'nombre')->get(),
            
        ];
    }

    public function getForForm(): array
    {
        $config = general_config();
        $countryId = $config?->country_id;

        return [
            'estados' => EstadosCliente::activos()->get(),
            'types'   => [
                'individual' => 'Persona Física', 
                'company'    => 'Empresa / Jurídica'
            ],
            // Filtramos estados solo del país configurado
            'states' => $countryId 
                ? State::byCountry($countryId)->select('id', 'name')->orderBy('name')->get()
                : collect(),
            // Tipos de ID Fiscal (RNC, Cédula, etc.) del país
            'taxIdentifierTypes' => $countryId 
                ? TaxIdentifierType::byCountry($countryId)->select('id', 'code', 'name')->orderBy('name')->get()
                : collect(),
        ];
    }
}