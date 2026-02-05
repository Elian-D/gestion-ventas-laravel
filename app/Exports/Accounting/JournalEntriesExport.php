<?php

namespace App\Exports\Accounting;

use App\Models\Accounting\JournalEntry;
use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, WithStyles, WithDefaultStyles, WithColumnWidths};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\{Style, Fill, Alignment, Border};

class JournalEntriesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithDefaultStyles, WithColumnWidths
{
    protected $query;
    protected $statuses;

    public function __construct($query)
    {
        $this->query = $query;
        $this->statuses = JournalEntry::getStatuses();
    }

    public function query()
    {
        // Importante: Cargamos relaciones para evitar N+1 durante el mapeo
        return $this->query->with(['creator']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha Contable',
            'Número de Asiento',
            'Referencia',
            'Concepto / Glosa',
            'Total Débito',
            'Total Crédito',
            'Estado',
            'Registrado por',
            'Fecha de Creación'
        ];
    }

    public function map($entry): array
    {
        return [
            $entry->id,
            $entry->entry_date->format('d/m/Y'),
            '#' . str_pad($entry->id, 6, '0', STR_PAD_LEFT),
            $entry->reference ?? 'N/A',
            $entry->description,
            $entry->total_debit, // Asumiendo que usas accessors o cargaste la suma
            $entry->total_credit,
            $this->statuses[$entry->status] ?? $entry->status,
            $entry->creator->name ?? 'Sistema',
            $entry->created_at->format('d/m/Y H:i'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,  'B' => 15, 'C' => 18, 'D' => 15, 'E' => 45,
            'F' => 15, 'G' => 15, 'H' => 15, 'I' => 20, 'J' => 20,
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'font' => ['name' => 'Segoe UI', 'size' => 10],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo Encabezado
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '4F46E5']], // Indigo 600
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Formato moneda para columnas F y G (Débito y Crédito)
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('F2:G' . $lastRow)->getNumberFormat()
            ->setFormatCode('#,##0.00');

        return [];
    }
}