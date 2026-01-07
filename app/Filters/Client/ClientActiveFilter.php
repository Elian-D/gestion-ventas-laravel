<?php

namespace App\Filters\Client;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class ClientActiveFilter implements FilterInterface
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        if ($this->request->filled('active')) {
            $query->where('active', (bool) $this->request->input('active'));
        }

        return $query;
    }
}
