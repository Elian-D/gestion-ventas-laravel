<?php

namespace App\Filters\Sales\InvoiceFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class InvoiceSearchFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('search');
        if (!$value) return $query;

        return $query->where(function($q) use ($value) {
            $q->where('invoice_number', 'like', "%{$value}%")
              ->orWhereHas('sale.client', function($subQ) use ($value) {
                  $subQ->where('name', 'like', "%{$value}%");
              });
        });
    }
}