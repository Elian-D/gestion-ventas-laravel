<?php

namespace App\Filters\BusinessTypes;

use App\Filters\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BusinessTypesActiveFilter implements FilterInterface
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        $active = $this->request->input('activo');

        if ($active === null || $active === '') {
            return $query;
        }

        return $query->where('activo', (bool) $active);
    }
}
