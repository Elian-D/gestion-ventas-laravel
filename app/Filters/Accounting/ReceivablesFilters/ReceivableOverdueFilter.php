<?php

namespace App\Filters\Accounting\ReceivablesFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;
use Carbon\Carbon;

class ReceivableOverdueFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('overdue'); // 'yes' o 'no'
        if (!$value) return $query;

        $today = Carbon::now()->format('Y-m-d');

        return $value === 'yes' 
            ? $query->where('due_date', '<', $today)->where('status', '!=', 'paid')
            : $query->where('due_date', '>=', $today);
    }
}