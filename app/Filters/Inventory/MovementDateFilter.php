<?php

namespace App\Filters\Inventory;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;
use Illuminate\Support\Carbon;

class MovementDateFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $from = $this->request->input('from_date');
        $to = $this->request->input('to_date');

        return $query
            ->when($from, function($q) use ($from) {
                // Carbon parsea el formato "2026-02-01T09:30" automáticamente
                $date = Carbon::parse($from)->startOfMinute(); 
                return $q->where('created_at', '>=', $date->format('Y-m-d H:i:s'));
            })
            ->when($to, function($q) use ($to) {
                $date = Carbon::parse($to)->endOfMinute();
                return $q->where('created_at', '<=', $date->format('Y-m-d H:i:s'));
            });
    }
}