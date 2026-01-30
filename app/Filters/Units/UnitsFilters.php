<?php

namespace App\Filters\Units;

use App\Filters\Base\QueryFilter;

class UnitsFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'search'               => UnitsSearchFilter::class,
            'is_active'            => UnitsActiveFilter::class,
        ];
    }
}
