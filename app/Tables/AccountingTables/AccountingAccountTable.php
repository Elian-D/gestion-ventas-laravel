<?php

namespace App\Tables\AccountingTables;

class AccountingAccountTable
{
    public static function allColumns(): array
    {
        return [
            'code'          => 'Código',
            'name'          => 'Cuenta',
            'type'          => 'Tipo', // Nombre correcto de la columna
            'parent_id'     => 'Padre',
            'level'         => 'Nivel',
            'is_selectable' => 'Posteable', // Término contable para "Recibe asientos"
            'is_active'     => 'Estado',
            'created_at'        => 'Fecha Creación',
            'updated_at'        => 'Última Actualización',
        ];
    }

    public static function defaultDesktop(): array
    {
        return [
            'code',
            'name',
            'type',
            'is_selectable',
            'is_active',
        ];
    }

    public static function defaultMobile(): array
    {
        return ['code', 'name'];
    }
}