<?php

namespace App\Tables\AccountingTables;

class JournalEntryTable
{
    public static function allColumns(): array
    {
        return [
            'entry_date'  => 'Fecha',
            'number'      => 'Número', // Id o correlativo interno
            'reference'   => 'Referencia',
            'description' => 'Concepto / Glosa',
            'debit'       => 'Total Débito',
            'credit'      => 'Total Crédito',
            'status'      => 'Estado',
            'created_by'  => 'Creado por',
            'updated_at'  => 'Última Actualización',
        ];
    }

    public static function defaultDesktop(): array
    {
        return [
            'entry_date',
            'reference',
            'description',
            'debit',
            'status',
        ];
    }

    public static function defaultMobile(): array
    {
        // En móvil lo más importante es saber qué se hizo y cuánto fue
        return ['entry_date', 'description', 'debit'];
    }
}