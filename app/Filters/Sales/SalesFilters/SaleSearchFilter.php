<?php

namespace App\Filters\Sales\SalesFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class SaleSearchFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('search');
        if (!$value) return $query;

        return $query->where(function($q) use ($value) {
            $q->where('number', 'like', "%{$value}%")
              ->orWhere('notes', 'like', "%{$value}%")
              ->orWhereHas('client', function($subQ) use ($value) {
                  $subQ->where('name', 'like', "%{$value}%");
              });
        });
    }
}