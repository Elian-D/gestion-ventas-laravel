<?php

namespace App\Tables;

class InventoryStockTable
{
    public static function allColumns(): array
    {
        return [
            // Columnas sugeridas:
            'product_id'    => 'Producto',
            'warehouse_id'  => 'Almacén',
            'quantity'   => 'Stock Actual',
            'min_stock'  => 'Stock Mínimo',
            'status'     => 'Estado', // Cálculo dinámico: quantity < min_stock
            'created_at'        => 'Fecha Creación',
            'updated_at'        => 'Última Actualización',
        ];
    }

    public static function defaultDesktop(): array
    {
        return [
            'product_id',
            'warehouse_id',
            'quantity',
            'status',
        ];
    }

    public static function defaultMobile(): array
    {
        return [
            'product_id',
            'warehouse_id',
            'quantity',
            'status',
        ];
    }
}
