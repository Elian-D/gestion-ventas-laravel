<?php

namespace App\Filters\PointOfSale;

use App\Filters\Base\QueryFilter;

class PointOfSaleFilters extends QueryFilter 
{
    protected function filters(): array 
    {
        return [
            'search'        => POSSearchFilter::class,
            'client'        => POSClientFilter::class,
            'business_type' => POSTypeFilter::class,
            'state'         => POSStateFilter::class,
            'active'        => POSActiveFilter::class,
        ];
    }
}