<?php

namespace App\Filters\Accounting\DocumentTypeFilters;

use App\Filters\Base\QueryFilter;

class DocumentTypeFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'search' => DocumentTypeNameFilter::class,
            'active' => DocumentTypeStatusFilter::class,
        ];
    }
}