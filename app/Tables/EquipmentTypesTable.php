<?php

namespace App\Tables;

class EquipmentTypesTable
{
    public static function allColumns(): array
    {
        return [
            'id'                => 'ID',
            'nombre'            => 'Nombre',
            'prefix'            => 'Prefijo',
            'activo'            => 'Estado',
            'created_at'        => 'Fecha Creación',
            'updated_at'        => 'Última Actualización',
        ];
    }

    public static function defaultDesktop(): array
    {
        return [
            'id',
            'nombre',
            'prefix',
            'activo',
        ];
    }

    public static function defaultMobile(): array
    {
        return [
            'id',
            'nombre',
        ];
    }
}
