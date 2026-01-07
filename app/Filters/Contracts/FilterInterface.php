<?php

namespace App\Filters\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterInterface
{
    public function apply(Builder $query): Builder;
}
