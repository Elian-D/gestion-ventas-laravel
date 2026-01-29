<?php

namespace App\Tables;

class ClientTable
{
    public static function allColumns(): array
    {
        return [
            'id'                   => 'ID',
            'name'                 => 'Nombre Cliente',
            'tax_identifier_types' => 'Tipo Identificador Fiscal',
            'tax_id'               => 'Identificador Fiscal',
            'type'                 => 'Tipo de Cliente',
            'email'                => 'Email',
            'phone'                => 'Teléfono',
            'state'                => 'Estado/Provincia',
            'city'                 => 'Ciudad',
            'address'              => 'Dirección',
            'estado_cliente'       => 'Estado del Cliente',
            'created_at'           => 'Fecha Creación',
            'updated_at'           => 'Última Actualización'
        ];
    }

    public static function defaultDesktop(): array
    {
        return ['id', 'name', 'tax_identifier_types','tax_id', 'estado_cliente'];
    }

    public static function defaultMobile(): array
    {
        return ['id', 'name'];
    }
}