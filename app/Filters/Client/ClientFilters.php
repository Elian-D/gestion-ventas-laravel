<?php

namespace App\Filters\Client;

use App\Filters\Base\QueryFilter;

class ClientFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'search'           => ClientSearchFilter::class,
            'estado_cliente'   => ClientBusinessStatusFilter::class,
            'state'          => ClientStateFilter::class,        
            'type'           => ClientTypeFilter::class,         
            'tax_type'       => ClientTaxIdentifierFilter::class,
            'from_date' => ClientDateFilter::class,
            'to_date'   => ClientDateFilter::class,
        ];
    }
}
