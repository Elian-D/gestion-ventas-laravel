<?php

namespace App\Filters\Client;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class ClientBusinessStatusFilter implements FilterInterface
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        if ($this->request->filled('estado_cliente')) {
            $query->where(
                'estado_cliente_id',
                $this->request->input('estado_cliente')
            );
        }

        return $query;
    }
}
