<?php

namespace App\Filters\Equipment;

use App\Filters\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EquipmentTypeFilter implements FilterInterface
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder
    {
        $typeId = $this->request->input('equipment_type_id');

        return $typeId
            ? $query->where('equipment_type_id', $typeId)
            : $query;
    }
}
