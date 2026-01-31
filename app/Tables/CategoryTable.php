<?php

namespace App\Tables;

class CategoryTable
{
    public static function allColumns(): array
    {
        return [
            'id'                => 'ID',
            'name'              => 'Nombre',
            'is_active'         => 'Estado',
            'description'       => 'Descripción',
            'created_at'        => 'Fecha Creación',
            'updated_at'        => 'Última Actualización',
        ];
    }

    public static function defaultDesktop(): array
    {
        return [
            'id',
            'name',
            'is_active',
            'description',
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
