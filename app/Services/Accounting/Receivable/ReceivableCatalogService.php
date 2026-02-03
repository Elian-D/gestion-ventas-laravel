<?php

namespace App\Services\Accounting\Receivable;

use App\Models\Accounting\AccountingAccount;
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
            // Solo clientes que tienen o han tenido deudas para no saturar el filtro
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
    /**
     * Datos para formularios (Creación manual de deuda / Ajustes)
     */
    public function getForForm(): array
    {
        // Obtenemos la cuenta base (CxC Clientes locales/general)
        $defaultAccount = AccountingAccount::where('code', '1.1.02')->first();

        return [
            'clients' => Client::where('credit_limit', '>', 0)
                ->with('accountingAccount:id,code,name') 
                ->select('id', 'name', 'credit_limit', 'balance', 'payment_terms', 'accounting_account_id')
                ->orderBy('name')
                ->get(),
            
            'defaultAccount' => $defaultAccount,

            // Solo cuentas que cuelguen de 1.1.02 (Cuentas por Cobrar)
            'accounts' => AccountingAccount::where('is_selectable', true)
                ->where('code', 'like', '1.1.02%') 
                ->select('id', 'code', 'name')
                ->orderBy('code')
                ->get(),

            'statuses' => Receivable::getStatuses()
        ];
    }
}