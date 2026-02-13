<?php

namespace App\Services\Sales\InvoicesServices;

use App\Models\Sales\Invoice;
use App\Models\Clients\Client;

class InvoiceCatalogService
{
    /**
     * Datos para los filtros de la tabla de Facturas.
     * Solo traemos clientes que tengan al menos una factura generada.
     */
    public function getForFilters(): array
    {
        return [
            // Solo clientes que realmente tengan documentos emitidos
            'clients' => Client::whereHas('sales.invoice')
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),

            // Tipos de pago (Contado/Crédito) mapeados desde Sale
            'payment_types' => [
                'cash'   => 'Contado',
                'credit' => 'Crédito'
            ],

            // Estados del documento legal
            'statuses' => Invoice::getStatuses(),

            // Formatos de impresión disponibles
            'formats' => Invoice::getFormats(),
        ];
    }
}