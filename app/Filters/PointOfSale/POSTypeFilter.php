<?php

namespace App\Filters\PointOfSale;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class POSTypeFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('business_type');
        return $value ? $query->where('business_type_id', $value) : $query;
    }
}