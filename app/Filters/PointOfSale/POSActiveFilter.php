<?php

namespace App\Filters\PointOfSale;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class POSActiveFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('active');
        // Validamos explícitamente porque es un booleano (0 o 1)
        return ($value !== null && $value !== '') ? $query->where('active', $value) : $query;
    }
}