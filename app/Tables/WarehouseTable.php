<?php

namespace App\Tables;

class WarehouseTable
{
    public static function allColumns(): array
    {
        return [
            'code'              => 'Código',
            'name'              => 'Nombre',
            'types'             => 'Tipo',
            'address'           => 'Ubicación',
            'description'       => 'Descripción',
            'is_active'         => 'Estado',
            'created_at'        => 'Fecha Creación',
            'updated_at'        => 'Última Actualización',
        ];
    }

    public static function defaultDesktop(): array
    {
        return [
            'code',
            'name',
            'types',
            'is_active',
        ];
    }

    public static function defaultMobile(): array
    {
        return [
            'code',
            'name',
        ];
    }
}
