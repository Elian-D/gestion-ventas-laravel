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
            'city'                 => 'Ciudad',
            'state'                => 'Estado/Provincia',
            'estado_cliente'       => 'Estado del Cliente',
            'created_at'           => 'Fecha Creación',
            'updated_at'           => 'Última Actualización'
        ];
    }

    public static function defaultDesktop(): array
    {
        return ['id', 'name', 'tax_id', 'city', 'state', 'estado_cliente'];
    }

    public static function defaultMobile(): array
    {
        return ['id', 'name'];
    }
}