<?php

namespace App\Filters\Inventory;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class MovementSearchFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('search');
        if (!$value) return $query;

        return $query->where(function($q) use ($value) {
            $q->whereHas('product', fn($p) => $p->where('name', 'like', "%{$value}%"))
              ->orWhere('description', 'like', "%{$value}%");
        });
    }
}