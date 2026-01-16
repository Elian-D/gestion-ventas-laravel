<?php

namespace App\Exports\Clients;

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

        public function __construct($query, $columns = [])
        {
            $this->query = $query;
            $this->columns = $columns;
        }

        public function query()
        {
            return $this->query;
        }

        public function headings(): array
        {
            // Si quieres que sea compatible con el IMPORT, 
            // los headings DEBEN ser iguales a los del ClientsImport
            return [
                'tipo', 
                'nombre_o_razon_social', 
                'nombre_comercial', 
                'email', 
                'telefono', 
                'provincia_estado', 
                'ciudad', 
                'tipo_identificacion', 
                'rnc_cedula', 
                'estado_cliente', 
                'activo',
                'fecha_registro', 
                'ultima_actualizacion'
            ];
        }

        public function map($client): array
        {
            // Transformamos los datos de la DB a texto legible por el Importador
            return [
                $client->type === 'company' ? 'Empresa' : 'Individual',
                $client->name,
                $client->commercial_name,
                $client->email,
                $client->phone,
                $client->state?->name,             // Nombre de provincia, no ID
                $client->city,
                $client->taxIdentifierType?->name, // Nombre de tipo ID, no ID
                $client->tax_id,
                $client->estadoCliente?->nombre,   // Nombre de estado, no ID
                $client->active ? 'Si' : 'No',
                $client->created_at->format('d-m-Y H:i'),
                $client->updated_at->format('d-m-Y H:i'),
            ];
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