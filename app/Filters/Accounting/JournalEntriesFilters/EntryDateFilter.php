<?php

namespace App\Filters\Accounting\JournalEntriesFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;
use Illuminate\Support\Carbon;

class EntryDateFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $from = $this->request->input('from_date');
        $to = $this->request->input('to_date');

        return $query
            ->when($from, function($q) use ($from) {
                $date = Carbon::parse($from)->startOfDay(); 
                return $q->where('entry_date', '>=', $date->format('Y-m-d'));
            })
            ->when($to, function($q) use ($to) {
                $date = Carbon::parse($to)->endOfDay();
                return $q->where('entry_date', '<=', $date->format('Y-m-d'));
            });
    }
}