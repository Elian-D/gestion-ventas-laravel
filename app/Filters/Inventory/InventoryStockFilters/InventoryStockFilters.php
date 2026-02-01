<?php

namespace App\Filters\Inventory\InventoryStockFilters;

use App\Filters\Base\QueryFilter;

class InventoryStockFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'search'       => InventoryStockSearchFilter::class,
            'warehouse_id' => InventoryStockWarehouseFilter::class,
            'category_id'  => InventoryStockCategoryFilter::class,
            'status'       => InventoryStockStatusFilter::class,
        ];
    }
}