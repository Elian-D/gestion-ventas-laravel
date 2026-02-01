<?php

namespace App\Filters\Inventory\InventoryStockFilters;

use App\Filters\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InventoryStockSearchFilter implements FilterInterface
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        $search = $this->request->input('search');
        if (!$search) return $query;

        return $query->whereHas('product', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });
    }
}