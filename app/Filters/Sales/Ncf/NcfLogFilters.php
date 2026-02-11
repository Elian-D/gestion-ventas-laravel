<?php

namespace App\Filters\Sales\Ncf;

use App\Filters\Base\QueryFilter;

class NcfLogFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'ncf_type_id' => NcfTypeFilter::class, // Reutilizado
            'status'      => NcfLogStatusFilter::class,
            'from_date'   => NcfLogDateFilter::class,
            'search'      => NcfLogSearchFilter::class, // Para buscar por NCF específico
        ];
    }
}