<?php

namespace App\Filters\Client;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class ClientTaxIdentifierFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('tax_type');
        return $value ? $query->where('tax_identifier_type_id', $value) : $query;
    }
}