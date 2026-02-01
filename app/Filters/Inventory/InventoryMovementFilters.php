<?php

namespace App\Filters\Inventory;

use App\Filters\Base\QueryFilter;
use Illuminate\Http\Request;

class InventoryMovementFilters extends QueryFilter 
{
    protected function filters(): array 
    {
        return [
            'search'       => MovementSearchFilter::class,
            'warehouse_id' => MovementWarehouseFilter::class, 
            'type'         => MovementTypeFilter::class,      
            'from_date'    => MovementDateFilter::class,
            'to_date'      => MovementDateFilter::class,
        ];
    }
}