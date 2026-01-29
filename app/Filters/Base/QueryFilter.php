<?php

namespace App\Filters\Base;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\Contracts\FilterInterface;

abstract class QueryFilter implements FilterInterface
{
    protected Request $request;
    protected Builder $query;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        $this->query = $query;

        foreach ($this->filters() as $key => $filterClass) {
            if ($this->request->filled($key)) {
                (new $filterClass($this->request))->apply($this->query);
            }
        }

        return $this->query;
    }

    /**
     * Mapa: request_key => FilterClass
     */
    abstract protected function filters(): array;
}
