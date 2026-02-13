<?php

namespace App\Filters\Sales\Ncf;

use App\Filters\Base\QueryFilter;

class NcfSequenceFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'ncf_type_id' => NcfTypeFilter::class,
            'status'      => NcfSequenceStatusFilter::class,
        ];
    }
}