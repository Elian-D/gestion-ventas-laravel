<?php

namespace App\Filters\Accounting\PaymentsFilters;

use App\Filters\Base\QueryFilter;

class PaymentFilters extends QueryFilter
{
    protected function filters(): array
    {
        return [
            'search'       => PaymentSearchFilter::class,
            'client_id'    => PaymentClientFilter::class,
            'tipo_pago_id' => PaymentMethodFilter::class,
            'status'       => PaymentStatusFilter::class,
            'from_date'    => PaymentDateFilter::class, // Maneja to_date internamente
            'min_amount'   => PaymentAmountRangeFilter::class, // Maneja max_amount internamente
        ];
    }
}