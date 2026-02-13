<?php

namespace App\Filters\Sales\InvoiceFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class InvoiceClientFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('client_id');
        // Filtramos a través de la relación sale
        return $value ? $query->whereHas('sale', fn($q) => $q->where('client_id', $value)) : $query;
    }
}