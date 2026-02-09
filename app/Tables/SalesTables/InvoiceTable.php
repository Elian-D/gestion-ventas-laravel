<?php

namespace App\Tables\SalesTables;

class InvoiceTable
{
    /**
     * Definición de todas las columnas disponibles para el histórico de facturas.
     */
    public static function allColumns(): array
    {
        return [
            'invoice_number' => 'N° Factura',
            'sale_id'        => 'Venta Origen', // Referencia al folio de venta
            'client_id'      => 'Cliente',      // A través de la relación sale
            'type'           => 'Tipo Venta',   // Cash / Credit
            'format_type'    => 'Formato',      // Ticket / Carta / Ruta
            'total_amount'   => 'Monto Total',  // Cargado desde la relación sale
            'status'         => 'Estado Doc.',  // Vigente / Anulada
            'due_date'       => 'Vencimiento',  // Para facturas a crédito
            'generated_by'   => 'Emitido por',
            'created_at'     => 'Fecha Emisión',
        ];
    }

    /**
     * Columnas visibles por defecto en escritorio.
     */
    public static function defaultDesktop(): array
    {
        return [
            'created_at',
            'invoice_number',
            'client_id',
            'type',
            'total_amount',
            'status',
            'format_type',
        ];
    }

    /**
     * Columnas críticas para móviles.
     */
    public static function defaultMobile(): array
    {
        return [
            'invoice_number',
            'total_amount',
            'status',
        ];
    }
}