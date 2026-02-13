<?php

namespace App\Filters\Sales\InvoiceFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class InvoiceFormatFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('format_type');
        return $value ? $query->where('format_type', $value) : $query;
    }
}