<?php

namespace App\Exports\Inventory;

use App\Models\Inventory\Warehouse;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class InventoryStockExport implements 
    FromQuery, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithDefaultStyles,
    WithColumnWidths
{
    protected $query;
    private $warehousesCache = [];

    public function __construct($query)
    {
        // El query ya viene filtrado desde el controlador
        $this->query = $query;
        $this->loadCaches();
    }

    private function loadCaches()
    {
        $this->warehousesCache = Warehouse::pluck('name', 'id')->toArray();
    }

    public function query()
    {
        return $this->query
            ->with(['product.category', 'product.unit']) // Cargamos relaciones necesarias
            ->select([
                'id', 'warehouse_id', 'product_id', 
                'quantity', 'min_stock', 'updated_at'
            ]);
    }

    public function headings(): array
    {
        return [
            'ID', 
            'Almacén', 
            'Producto', 
            'SKU',
            'Categoría',
            'Stock Actual', 
            'Unidad',
            'Stock Mínimo', 
            'Estado',
            'Última Actualización'
        ];
    }

    public function map($stock): array
    {
        $status = 'OK';
        if ($stock->quantity <= 0) {
            $status = 'AGOTADO';
        } elseif ($stock->quantity <= $stock->min_stock) {
            $status = 'STOCK BAJO';
        }

        return [
            $stock->id,
            $this->warehousesCache[$stock->warehouse_id] ?? 'N/A',
            $stock->product->name,
            $stock->product->sku ?? 'N/A',
            $stock->product->category->name ?? 'N/A',
            $stock->quantity,
            $stock->product->unit->abbreviation ?? '',
            $stock->min_stock,
            $status,
            $stock->updated_at->format('d-m-Y H:i'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,  'B' => 25, 'C' => 35, 'D' => 15, 'E' => 20,
            'F' => 15, 'G' => 10, 'H' => 15, 'I' => 15, 'J' => 20,
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return ['font' => ['name' => 'Arial', 'size' => 11]];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '3B82F6'], // Azul Blue-500 para Inventario
                ]
            ],
        ];
    }
}