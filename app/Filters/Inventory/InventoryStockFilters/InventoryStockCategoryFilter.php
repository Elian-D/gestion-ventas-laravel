<?php

namespace App\Filters\Inventory\InventoryStockFilters;

use App\Filters\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InventoryStockCategoryFilter implements FilterInterface
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder {
        $value = $this->request->input('category_id');
        if (!$value) return $query;
        
        return $query->whereHas('product', function ($q) use ($value) {
            $q->where('category_id', $value);
        });
    }
}