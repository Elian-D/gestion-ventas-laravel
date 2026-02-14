<?php

namespace App\Filters\Sales\Pos\SessionFilters;

use App\Filters\Base\QueryFilter;

class PosSessionFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'terminal_id' => PosSessionTerminalFilter::class,
            'user_id'     => PosSessionUserFilter::class,
            'status'      => PosSessionStatusFilter::class,
            'from_date'   => PosSessionDateFilter::class,
        ];
    }
}