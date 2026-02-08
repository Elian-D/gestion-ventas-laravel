<?php

namespace App\Services\Accounting\Receivable;

use App\Models\Clients\Client;
use App\Models\Accounting\Receivable;

class ReceivableCatalogService
{
    /**
     * Datos para los filtros de la tabla de CxC
     */
    public function getForFilters(): array
    {
        return [
            'clients' => Client::whereHas('receivables')
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),

            'statuses' => Receivable::getStatuses(),

            'overdueOptions' => [
                'yes' => 'Facturas Vencidas',
                'no'  => 'Al Día'
            ]
        ];
    }
}