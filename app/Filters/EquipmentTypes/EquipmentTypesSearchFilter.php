<?php

namespace App\Filters\EquipmentTypes;

use App\Filters\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EquipmentTypesSearchFilter implements FilterInterface
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        $search = $this->request->input('search');

        if (! $search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('nombre', 'like', "%{$search}%")
              ->orWhere('prefix', 'like', "%{$search}%");
        });
    }
}
