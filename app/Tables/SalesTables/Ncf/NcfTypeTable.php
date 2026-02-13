<?php

namespace App\Tables\SalesTables\Ncf;

class NcfTypeTable
{
    /**
     * Columnas para la configuración de Tipos de NCF.
     */
    public static function allColumns(): array
    {
        return [
            'name'           => 'Nombre',
            'prefix'         => 'Prefijo',
            'code'           => 'Código',
            'is_electronic'  => 'Electrónico',
            'requires_rnc'   => 'Requiere RNC',
            'is_active'      => 'Estado',
            'sequences_count' => 'Secuencias Activas',
            'created_at'     => 'Fecha Creación',
        ];
    }

    public static function defaultDesktop(): array
    {
        return [
            'name',
            'prefix',
            'code',
            'is_electronic',
            'requires_rnc',
            'is_active',
        ];
    }

    public static function defaultMobile(): array
    {
        return [
            'name',
            'code',
            'is_active'
        ];
    }
}