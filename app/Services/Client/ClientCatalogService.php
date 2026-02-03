<?php

namespace App\Services\Client;

use App\Models\Geo\State;
use App\Models\Configuration\EstadosCliente;
use App\Models\Configuration\TaxIdentifierType;
use App\Models\Accounting\AccountingAccount; // Importante

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

            // Opciones estáticas para los nuevos filtros de deuda
            'debtOptions' => [
                'yes' => 'Con Saldo Pendiente',
                'no'  => 'Sin Deuda'
            ]
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
            'states' => $countryId 
                ? State::byCountry($countryId)->select('id', 'name')->orderBy('name')->get()
                : collect(),
            'taxIdentifierTypes' => $countryId 
                ? TaxIdentifierType::byCountry($countryId)->select('id', 'code', 'name')->orderBy('name')->get()
                : collect(),
            
            // Filtramos por el nodo de Activos Corrientes -> Cuentas por Cobrar
            'accountingAccounts' => AccountingAccount::where('is_selectable', true)
                ->where('code', 'like', '1.1.02%') // Ajusta según tu plan de cuentas
                ->select('id', 'code', 'name')
                ->orderBy('code')
                ->get(),
        ];
    }
}