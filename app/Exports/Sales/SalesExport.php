<?php

namespace App\Exports\Sales;

use App\Models\Sales\Sale;
use App\Models\Clients\Client;
use App\Models\Inventory\Warehouse;
use App\Models\User;
use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, WithStyles, WithDefaultStyles, WithColumnWidths};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\{Style, Fill, Alignment, Border};

class SalesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithDefaultStyles, WithColumnWidths
{
    protected $query;
    private $clientsCache = [];
    private $warehousesCache = [];
    private $usersCache = [];
    private $statusLabels = [];
    private $paymentLabels = [];

    public function __construct($query)
    {
        $this->query = $query;
        
        // Carga de caches para optimizar el mapeo de miles de filas
        $this->clientsCache = Client::pluck('name', 'id')->toArray();
        $this->warehousesCache = Warehouse::pluck('name', 'id')->toArray();
        $this->usersCache = User::pluck('name', 'id')->toArray();
        $this->statusLabels = Sale::getStatuses();
        $this->paymentLabels = Sale::getPaymentTypes();
    }

    /**
     * Consulta base filtrada desde el controlador
     */
    public function query()
    {
        return $this->query->select([
            'id', 'number', 'created_at', 'client_id', 'warehouse_id', 
            'payment_type', 'total_amount', 'status', 'user_id', 'notes'
        ])->latest('sale_date');
    }

    /**
     * Encabezados del reporte
     */
    public function headings(): array
    {
        return [
            'ID',
            'Número Doc',
            'Fecha',
            'Cliente',
            'Almacén',
            'Tipo de Pago',
            'Total Venta',
            'Estado',
            'Vendedor',
            'Observaciones'
        ];
    }

    /**
     * Mapeo de datos para el Excel
     */
    public function map($sale): array
    {
        return [
            $sale->id,
            $sale->number,
            $sale->created_at->format('d/m/Y H:i'),
            $this->clientsCache[$sale->client_id] ?? 'N/A',
            $this->warehousesCache[$sale->warehouse_id] ?? 'N/A',
            $this->paymentLabels[$sale->payment_type] ?? $sale->payment_type,
            $sale->total_amount,
            $this->statusLabels[$sale->status] ?? $sale->status,
            $this->usersCache[$sale->user_id] ?? 'Sistema',
            $sale->notes ?? '',
        ];
    }

    /**
     * Anchos de columna optimizados
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,  'B' => 15, 'C' => 20, 'D' => 35, 'E' => 22,
            'F' => 15, 'G' => 18, 'H' => 15, 'I' => 20, 'J' => 45,
        ];
    }

    /**
     * Estilo base (Segoe UI)
     */
    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'font' => ['name' => 'Segoe UI', 'size' => 10],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ];
    }

    /**
     * Formato de moneda, colores y bordes
     */
    public function styles(Worksheet $sheet)
    {
        // Encabezado Pro (Indigo 600)
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $lastRow = $sheet->getHighestRow();

        // Formato Moneda para la columna de Total
        $sheet->getStyle('G2:G' . $lastRow)->getNumberFormat()
            ->setFormatCode('"$"#,##0.00');

        // Bordes de tabla (Gray 200)
        $sheet->getStyle('A1:J' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'E5E7EB'],
                ],
            ],
        ]);

        return [];
    }
}