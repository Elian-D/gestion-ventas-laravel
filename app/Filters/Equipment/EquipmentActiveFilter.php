<?php

namespace App\Filters\Equipment;

use App\Filters\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EquipmentActiveFilter implements FilterInterface
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        $active = $this->request->input('active');

        if ($active === null || $active === '') {
            return $query;
        }

        return $query->where('active', (bool) $active);
    }
}
