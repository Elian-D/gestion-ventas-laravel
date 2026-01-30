<?php

namespace App\Filters\Categories;

use App\Filters\Base\QueryFilter;

class CategoryFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'search'               => CategorySearchFilter::class,
            'is_active'            => CategoryActiveFilter::class,
        ];
    }
}
