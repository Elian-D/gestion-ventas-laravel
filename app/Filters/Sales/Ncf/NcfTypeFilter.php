<?php

namespace App\Filters\Sales\Ncf;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class NcfTypeFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('ncf_type_id');
        return $value ? $query->where('ncf_type_id', $value) : $query;
    }
}