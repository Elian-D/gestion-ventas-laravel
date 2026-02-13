<?php

namespace App\Tables\SalesTables\Ncf;

class NcfSequenceTable
{
    /**
     * Definición de todas las columnas disponibles para la gestión de secuencias NCF.
     */
    public static function allColumns(): array
    {
        return [
            'type_id'         => 'Tipo de Comprobante',
            'series'          => 'Serie',
            'range'           => 'Rango (Desde - Hasta)',
            'current'         => 'Último Usado',
            'available'       => 'Disponibles',
            'usage_percent'   => '% de Uso',
            'expiry_date'     => 'Vencimiento',
            'alert_threshold' => 'Umbral Alerta',
            'status'          => 'Estado',
            'created_at'      => 'Fecha Registro',
        ];
    }

    /**
     * Columnas visibles por defecto en resolución de escritorio.
     */
    public static function defaultDesktop(): array
    {
        return [
            'type_id',
            'range',
            'current',
            'available',
            'usage_percent',
            'expiry_date',
            'status',
        ];
    }

    /**
     * Columnas críticas para visualización en dispositivos móviles.
     */
    public static function defaultMobile(): array
    {
        return [
            'type_id', 
            'current', 
            'status'
        ];
    }
}