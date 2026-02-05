<?php

namespace App\Services\Accounting\JournalEntries;

use App\Models\Accounting\AccountingAccount;
use App\Models\Accounting\JournalEntry;

class JournalEntryCatalogService
{
    /**
     * Datos necesarios para los filtros de la tabla index.
     */
    public function getForFilters(): array
    {
        return [
            'statuses' => JournalEntry::getStatuses(),
            'status_styles' => JournalEntry::getStatusStyles(),
        ];
    }

    /**
     * Datos necesarios para el formulario de creación/edición.
     * Solo retorna cuentas "posteables" (is_selectable).
     */
    public function getForForm(): array
    {
        return [
            'accounts' => AccountingAccount::where('is_selectable', true)
                ->where('is_active', true)
                ->select('id', 'code', 'name')
                ->orderBy('code')
                ->get(),
            'statuses' => JournalEntry::getStatuses(),
        ];
    }
}