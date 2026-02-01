<?php

namespace App\Filters\Products;

use App\Filters\Base\QueryFilter;

class ProductsFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'search'               => ProductsSearchFilter::class,
            'is_active'            => ProductsActiveFilter::class,
            'categories'           => ProductsCategoryFilter::class,
            'units'                => ProductsUnitFilter::class,
        ];
    }
}
