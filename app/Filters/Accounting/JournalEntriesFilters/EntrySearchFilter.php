<?php

namespace App\Filters\Accounting\JournalEntriesFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class EntrySearchFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('search');
        if (!$value) return $query;

        return $query->where(function($q) use ($value) {
            $q->where('reference', 'like', "%{$value}%")
              ->orWhere('description', 'like', "%{$value}%")
              ->orWhere('id', 'like', "%{$value}%");
        });
    }
}