<?php

namespace App\Filters\Sales\Ncf;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class NcfLogSearchFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('search');
        return $value ? $query->where('full_ncf', 'like', "%{$value}%") : $query;
    }
}