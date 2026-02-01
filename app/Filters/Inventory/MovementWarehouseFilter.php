<?php

namespace App\Filters\Inventory;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class MovementWarehouseFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('warehouse_id');
        return $value ? $query->where('warehouse_id', $value) : $query;
    }
}