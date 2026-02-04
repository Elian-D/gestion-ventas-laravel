<?php

namespace App\Services\Accounting\Payment;

use App\Models\Accounting\Payment;
use App\Models\Accounting\Receivable;
use App\Models\Clients\Client;
use App\Models\Configuration\TipoPago;

class PaymentCatalogService
{
    /**
     * Datos para los filtros de la tabla de Pagos
     */
    public function getForFilters(): array
    {
        return [
            // Solo clientes que han realizado pagos
            'clients' => Client::whereHas('payments')
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),

            'paymentMethods' => TipoPago::activo()
                ->select('id', 'nombre')
                ->orderBy('nombre')
                ->get(),

            'statuses' => Payment::getStatuses(),
        ];
    }

    /**
     * Datos para el formulario de nuevo pago (Recibo de Ingreso)
     */
    public function getForForm(): array
    {
        return [
            // Clientes que actualmente deben dinero
            'clients' => Client::where('balance', '>', 0)
                ->select('id', 'name', 'balance')
                ->orderBy('name')
                ->get(),

            'paymentMethods' => TipoPago::activo()
                ->select('id', 'nombre')
                ->orderBy('nombre')
                ->get(),

            // Solo facturas con saldo pendiente (para el selector de factura a pagar)
            'pendingReceivables' => Receivable::whereIn('status', [Receivable::STATUS_UNPAID, Receivable::STATUS_PARTIAL])
                ->select('id', 'client_id', 'document_number', 'current_balance', 'total_amount')
                ->get()
        ];
    }
}