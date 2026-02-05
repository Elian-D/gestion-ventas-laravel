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
            'parentAccounts' => AccountingAccount::with('client') // <--- Agregamos Eager Loading
                ->select('id', 'code', 'name')
                ->where('is_active', true)
                ->orderBy('code')
                ->get(),
            'accountTypes' => AccountingAccount::getTypes(),
        ];
    }
}