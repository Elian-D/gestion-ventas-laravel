<?php

namespace App\Tables;

class InventoryMovementTable
{
    public static function allColumns(): array
    {
        return [
            'created_at'     => 'Fecha/Hora',
            'product'        => 'Producto',
            'warehouse'      => 'Almacén',
            'type'           => 'Operación',
            'toWarehouse'    => 'Almacen de Destino',
            'quantity'       => 'Cant.',
            'balance'        => 'Balance', // Columna combinada visualmente
            'user'           => 'Responsable',
            'reference'      => 'Documento/Ref',
            'description'    => 'Observaciones',
        ];
    }

    public static function defaultDesktop(): array
    {
        return [
            'created_at',
            'product',
            'warehouse',
            'type',
            'quantity',
            'balance', // Es clave tenerlo a la vista
            'user',
        ];
    }

    public static function defaultMobile(): array
    {
        return [
            'product',
            'type',
            'quantity',
        ];
    }
}