<?php

namespace App\Filters\Products;

use App\Filters\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductsActiveFilter implements FilterInterface
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        $active = $this->request->input('is_active');

        if ($active === null || $active === '') {
            return $query;
        }

        return $query->where('is_active', (bool) $active);
    }
}
