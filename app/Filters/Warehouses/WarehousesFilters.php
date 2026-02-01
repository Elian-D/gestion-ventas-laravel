<?php

namespace App\Filters\Warehouses;

use App\Filters\Base\QueryFilter;

class WarehousesFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'search'    => WarehousesSearchFilter::class,
            'is_active' => WarehousesActiveFilter::class,
            'type'      => WarehousesTypeFilter::class,
        ];
    }
}