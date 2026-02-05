<?php

namespace App\Filters\Accounting\ReceivablesFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class ReceivableBalanceRangeFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $min = $this->request->input('min_balance');
        $max = $this->request->input('max_balance');

        return $query
            ->when($min, fn($q) => $q->where('current_balance', '>=', $min))
            ->when($max, fn($q) => $q->where('current_balance', '<=', $max));
    }
}