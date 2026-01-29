<?php

namespace App\Filters\BusinessTypes;

use App\Filters\Base\QueryFilter;

class BusinessTypesFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'search'            => BusinessTypesSearchFilter::class,
            'activo'            => BusinessTypesActiveFilter::class,
        ];
    }
}
