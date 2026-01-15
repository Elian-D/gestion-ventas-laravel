<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ClientsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithDefaultStyles
{
    protected $query;
    protected $columns;
    protected $allColumns;

    public function __construct($query, array $columns)
    {
        $this->query = $query;
        $this->columns = $columns; // Las llaves seleccionadas: ['id', 'cliente', ...]
        
        // Diccionario para traducir llaves a títulos legibles
        $this->allColumns = [
            'id' => 'ID',
            'cliente' => 'Cliente',
            'city' => 'Ciudad',
            'state' => 'Estado (Ubicacion)',
            'estado_cliente' => 'Estado del Cliente',
            'estado_operativo' => 'Estado Operativo',
            'created_at' => 'Fecha Creación',
            'updated_at' => 'Última Actualización'
        ];
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        $headers = [];
        foreach ($this->columns as $column) {
            if (isset($this->allColumns[$column])) {
                $headers[] = $this->allColumns[$column];
            }
        }
        return $headers;
    }

    public function map($client): array
    {
        $data = [];
        foreach ($this->columns as $column) {
            $data[] = match($column) {
                'id'               => $client->id,
                'cliente'          => $client->display_name,
                'city'             => $client->city,
                'state'            => $client->state->name ?? '—',
                'estado_cliente'   => $client->estadoCliente->nombre ?? '—',
                'estado_operativo' => $client->active ? 'Activo' : 'Inactivo',
                'created_at'       => $client->created_at->format('d/m/Y'),
                'updated_at'       => $client->updated_at->diffForHumans(),
                default            => '',
            };
        }
        return $data;
    }

    /**
     * Estilos por defecto para toda la hoja
     */
    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'font' => [
                'name' => 'Arial',
                'size' => 11,
            ],
        ];
    }

    /**
     * Estilos específicos por celdas o rangos
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la fila 1 (Encabezados)
            1 => [
                'font' => [
                    'bold' => true, 
                    'color' => ['argb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '4F46E5'], // Indigo-600 de Tailwind
                ]
            ],
        ];
    }
}