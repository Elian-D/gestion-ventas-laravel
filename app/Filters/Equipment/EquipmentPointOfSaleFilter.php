<?php

namespace App\Filters\Equipment;

use App\Filters\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EquipmentPointOfSaleFilter implements FilterInterface
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        $posId = $this->request->input('point_of_sale_id');

        return $posId
            ? $query->where('point_of_sale_id', $posId)
            : $query;
    }
}
