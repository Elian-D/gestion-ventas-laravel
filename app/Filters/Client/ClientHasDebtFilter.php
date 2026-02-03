<?php

namespace App\Filters\Client;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class ClientHasDebtFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('has_debt');
        if ($value === 'yes') {
            return $query->where('balance', '>', 0);
        }
        if ($value === 'no') {
            return $query->where('balance', '<=', 0);
        }
        return $query;
    }
}