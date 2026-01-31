<?php

namespace App\Filters\Products;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class ProductsCategoryFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('categories');
        return $value ? $query->where('category_id', $value) : $query;
    }
}