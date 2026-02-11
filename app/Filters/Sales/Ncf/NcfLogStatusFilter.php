<?php

namespace App\Filters\Sales\Ncf;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class NcfLogStatusFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    // app/Filters/Sales/Ncf/NcfLogStatusFilter.php
    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('status');

        // Si el valor llega como "voided" o "used"
        if ($value) {
            return $query->where('ncf_logs.status', $value);
        }

        return $query;
    }
}