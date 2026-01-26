<?php

namespace App\Tables;

class EquipmentTable
{
    public static function allColumns(): array
    {
        return [
            'code'              => 'Código',
            'name'              => 'Nombre',
            'equipment_type_id' => 'Tipo de Equipo',
            'point_of_sale_id'  => 'Punto de Venta',
            'serial_number'     => 'Serial',
            'model'             => 'Modelo',
            'active'            => 'Estado',
            'created_at'        => 'Fecha Creación',
            'updated_at'        => 'Última Actualización',
        ];
    }

    public static function defaultDesktop(): array
    {
        return [
            'code',
            'name',
            'equipment_type_id',
            'point_of_sale_id',
            'active',
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
