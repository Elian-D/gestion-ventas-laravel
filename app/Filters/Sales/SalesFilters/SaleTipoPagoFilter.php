<?php

namespace App\Filters\Sales\SalesFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class SaleTipoPagoFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('tipo_pago_id');
        
        // Solo filtramos si el valor existe en el request
        return $value ? $query->where('tipo_pago_id', $value) : $query;
    }
}