<?php

namespace App\Filters\Products;

use App\Filters\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductsSearchFilter implements FilterInterface
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        $search = $this->request->input('search');

        if (! $search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        });
    }
}
