<?php

namespace App\Filters\Equipment;

use App\Filters\Base\QueryFilter;

class EquipmentFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'search'            => EquipmentSearchFilter::class,
            'equipment_type_id' => EquipmentTypeFilter::class,
            'point_of_sale_id'  => EquipmentPointOfSaleFilter::class,
            'active'            => EquipmentActiveFilter::class,
        ];
    }
}
