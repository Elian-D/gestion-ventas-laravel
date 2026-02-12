<?php

namespace App\Exports\Sales\Ncf;

use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, WithStyles};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NcfLogsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    protected $query;

    public function __construct($query) { $this->query = $query; }

    public function query() 
    { 
        // Importante cargar relaciones para evitar N+1
        return $this->query->with(['sale.client', 'type', 'user']); 
    }

    public function headings(): array
    {
        return ['Fecha', 'NCF', 'Tipo', 'Venta #', 'RNC/Cédula', 'Cliente', 'Monto', 'ITBIS', 'Estado'];
    }

    public function map($log): array
    {
        return [
            $log->created_at->format('d/m/Y'),
            $log->full_ncf,
            $log->type->name,
            $log->sale->number,
            $log->sale->client->tax_id ?? 'N/A',
            $log->sale->client->name,
            $log->sale->total_amount, // Asumiendo que guardas el total
            $log->sale->total_amount * 0.18, // Cálculo rápido de ITBIS
            $log->status == 'used' ? 'Utilizado' : 'Anulado'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}