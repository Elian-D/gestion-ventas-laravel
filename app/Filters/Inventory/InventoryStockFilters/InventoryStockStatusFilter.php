<?php

namespace App\Filters\Inventory\InventoryStockFilters;

use App\Filters\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InventoryStockStatusFilter implements FilterInterface
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        $status = $this->request->input('status');

        if (!$status) return $query;

        return match ($status) {
            // Stock por debajo del mínimo (pero mayor a 0)
            'low' => $query->whereColumn('quantity', '<=', 'min_stock')
                           ->where('quantity', '>', 0),
            
            // Stock en cero o negativo
            'out' => $query->where('quantity', '<=', 0),
            
            // Stock saludable
            'ok' => $query->whereColumn('quantity', '>', 'min_stock'),
            
            default => $query,
        };
    }
}