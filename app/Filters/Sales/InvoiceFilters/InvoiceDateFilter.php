<?php

namespace App\Filters\Sales\InvoiceFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;
use Illuminate\Support\Carbon;

class InvoiceDateFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $from = $this->request->input('from_date');
        $to = $this->request->input('to_date');

        return $query
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', Carbon::parse($from)))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', Carbon::parse($to)));
    }
}