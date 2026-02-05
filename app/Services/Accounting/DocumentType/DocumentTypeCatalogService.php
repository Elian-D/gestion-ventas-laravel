<?php

namespace App\Services\Accounting\DocumentType;

use App\Models\Accounting\AccountingAccount;
use App\Models\Accounting\DocumentType;

class DocumentTypeCatalogService
{
    /**
     * Datos para los filtros de la tabla index.
     */
    public function getForFilters(): array
    {
        return [
            'active_options' => [
                '1' => 'Activos',
                '0' => 'Inactivos',
            ],
        ];
    }

    /**
     * Datos para formularios de creación/edición.
     */
    public function getForForm(): array
    {
        return [
            'accounts' => AccountingAccount::where('is_selectable', true)
                ->where('is_active', true)
                ->select('id', 'code', 'name')
                ->orderBy('code')
                ->get(),
        ];
    }
}