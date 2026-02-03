<?php

namespace App\Tables\AccountingTables;

class ReceivableTable
{
    public static function allColumns(): array
    {
        return [
            'emission_date'   => 'Fecha Emisión',
            'due_date'        => 'Vencimiento',
            'document_number' => 'No. Factura',
            'client'          => 'Cliente',
            'description'     => 'Concepto',
            'total_amount'    => 'Monto Original',
            'current_balance' => 'Saldo Pendiente',
            'accounting_account_id' => 'Cuenta Contable',
            'status'          => 'Estado',
            'updated_at'      => 'Último Movimiento',
        ];
    }

    public static function defaultDesktop(): array
    {
        return [
            'emission_date',
            'document_number',
            'client',
            'total_amount',
            'current_balance',
            'due_date',
            'status',
            'accounting_account_id',
        ];
    }

    public static function defaultMobile(): array
    {
        // En móvil: Quién, cuánto debe y qué tan urgente es (estado)
        return ['client', 'current_balance', 'status'];
    }
}