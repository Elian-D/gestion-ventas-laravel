<?php

namespace App\Filters\Warehouses;

use App\Filters\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class WarehousesTypeFilter implements FilterInterface
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        $type = $this->request->input('type');

        // Validamos que no esté vacío
        if (!$type) return $query;

        return $query->where('type', $type);
    }
}