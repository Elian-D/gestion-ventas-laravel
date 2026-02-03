<?php

namespace App\Filters\Accounting\ReceivablesFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class ReceivableStatusFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('status');
        return $value ? $query->where('status', $value) : $query;
    }
}