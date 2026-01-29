<?php

namespace App\Filters\PointOfSale;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class POSSearchFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('search');
        if (!$value) return $query;

        return $query->where(function ($q) use ($value) {
            $q->where('name', 'like', "%{$value}%")
              ->orWhere('code', 'like', "%{$value}%")
              ->orWhere('contact_name', 'like', "%{$value}%");
        });
    }
}