<?php

namespace App\Filters\Inventory\InventoryStockFilters;

use App\Filters\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InventoryStockWarehouseFilter implements FilterInterface
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder {
        $value = $this->request->input('warehouse_id');
        return $value ? $query->where('warehouse_id', $value) : $query;
    }
}