<?php

namespace App\Tables\SalesTables;

class SaleTable
{
    /**
     * Definición de todas las columnas disponibles para el módulo de ventas.
     */
    public static function allColumns(): array
    {
        return [
            'sale_date'    => 'Fecha',
            'number'       => 'Folio / Número',
            'client_id'    => 'Cliente',
            'warehouse_id' => 'Almacén',
            'payment_type' => 'Tipo de Pago',
            'tipo_pago_id' => 'Método de Pago',
            'total_amount' => 'Total',
            'status'       => 'Estado',
            'user_id'      => 'Vendedor',
            'notes'        => 'Notas',
            'created_at'   => 'Fecha Registro',
            'updated_at'   => 'Última Actualización',
        ];
    }

    /**
     * Columnas visibles por defecto en resolución de escritorio.
     */
    public static function defaultDesktop(): array
    {
        return [
            'sale_date',
            'number',
            'client_id',
            'payment_type',
            'total_amount',
            'status',
        ];
    }

    /**
     * Columnas críticas para visualización en dispositivos móviles.
     */
    public static function defaultMobile(): array
    {
        // En móvil priorizamos quién compró, cuánto y el estado del pago
        return [
            'client_id', 
            'total_amount', 
            'payment_type'
        ];
    }
}