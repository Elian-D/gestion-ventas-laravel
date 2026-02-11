<?php

namespace App\Tables\SalesTables\Ncf;

class NcfLogTable
{
    /**
     * Columnas para la auditoría de NCF.
     * Incluye datos de la venta y el cliente para facilitar reportes 607/608.
     */
    public static function allColumns(): array
    {
        return [
            'full_ncf'            => 'NCF',
            'type_id'             => 'Tipo',
            'sale_number'         => 'Venta #',
            'customer'            => 'Cliente',
            'customer_rnc'        => 'RNC/Cédula',
            'status'              => 'Estado',
            'cancellation_reason' => 'Motivo Anulación',
            'user_id'             => 'Usuario',
            'created_at'          => 'Fecha/Hora',
        ];
    }

    public static function defaultDesktop(): array
    {
        return [
            'full_ncf',
            'type_id',
            'sale_number',
            'customer',
            'status',
            'created_at',
        ];
    }

    public static function defaultMobile(): array
    {
        return [
            'full_ncf', 
            'status'
        ];
    }
}