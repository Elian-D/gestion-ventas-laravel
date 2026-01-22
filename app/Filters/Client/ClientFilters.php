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
            // 'business_type'    => ClientBusinessFilter::class,
        ];
    }
}
