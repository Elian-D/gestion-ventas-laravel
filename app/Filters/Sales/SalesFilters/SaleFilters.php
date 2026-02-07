<?php

namespace App\Filters\Sales\SalesFilters;

use App\Filters\Base\QueryFilter;

class SaleFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'search'       => SaleSearchFilter::class,
            'client_id'    => SaleClientFilter::class,
            'warehouse_id' => SaleWarehouseFilter::class,
            'payment_type' => SalePaymentTypeFilter::class,
            'status'       => SaleStatusFilter::class,
            'from_date'    => SaleDateFilter::class,
            'min_amount'   => SaleAmountRangeFilter::class,
        ];
    }
}