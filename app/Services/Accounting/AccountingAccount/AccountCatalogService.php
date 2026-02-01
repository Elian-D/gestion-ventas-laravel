<?php

namespace App\Services\Accounting\AccountingAccount;

use App\Models\Accounting\AccountingAccount;

class AccountCatalogService
{
    /**
     * Datos necesarios para los formularios (Selects en modales)
     */
    public function getForForm(): array
    {
        return [
            // Solo las cuentas que pueden ser padres (normalmente niveles 1, 2 y 3)
            // O simplemente todas para permitir jerarquía libre
            'parentAccounts' => AccountingAccount::select('id', 'code', 'name')
                ->where('is_active', true)
                ->orderBy('code')
                ->get(),
            
            // Los tipos definidos en el modelo
            'accountTypes' => AccountingAccount::getTypes(),
        ];
    }
}