<?php

namespace App\Tables;

class UnitsTable
{
    public static function allColumns(): array
    {
        return [
            'id'                => 'ID',
            'name'              => 'Nombre',
            'abbreviation'      => 'Abreviatura',
            'is_active'         => 'Estado',
            'created_at'        => 'Fecha Creación',
            'updated_at'        => 'Última Actualización',
        ];
    }

    public static function defaultDesktop(): array
    {
        return [
            'id',
            'name',
            'abbreviation',
            'is_active',
        ];
    }

    public static function defaultMobile(): array
    {
        return [
            'id',
            'name',
        ];
    }
}
