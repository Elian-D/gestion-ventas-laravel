<?php

namespace App\Filters\Accounting\DocumentTypeFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class DocumentTypeNameFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('search');
        
        return $value ? $query->where(function($q) use ($value) {
            $q->where('name', 'like', "%{$value}%")
              ->orWhere('code', 'like', "%{$value}%")
              ->orWhere('prefix', 'like', "%{$value}%");
        }) : $query;
    }
}