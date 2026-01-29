<?php

namespace App\Filters\EquipmentTypes;

use App\Filters\Base\QueryFilter;

class EquipmentTypesFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'search'            => EquipmentTypesSearchFilter::class,
            'activo'            => EquipmentTypesActiveFilter::class,
        ];
    }
}
