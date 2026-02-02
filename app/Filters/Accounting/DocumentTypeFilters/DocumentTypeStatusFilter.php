<?php

namespace App\Filters\Accounting\DocumentTypeFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class DocumentTypeStatusFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('active');
        
        // Manejamos '1' para activos, '0' para inactivos
        if ($value === '1') return $query->where('is_active', true);
        if ($value === '0') return $query->where('is_active', false);
        
        return $query;
    }
}