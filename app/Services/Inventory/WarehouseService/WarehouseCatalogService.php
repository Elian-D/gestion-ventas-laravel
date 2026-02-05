<?php

namespace App\Services\Inventory\WarehouseService;

use App\Models\Inventory\Warehouse;
use App\Models\Accounting\AccountingAccount;

class WarehouseCatalogService
{
    public function getForIndex(): array
    {
        return [
            'types' => Warehouse::getTypes(),
            // Cuentas hijas de Inventario para posibles re-asignaciones
            'accounts' => AccountingAccount::where('code', 'like', '1.1.03.%')
                ->orderBy('code')
                ->get(),
        ];
    }
}