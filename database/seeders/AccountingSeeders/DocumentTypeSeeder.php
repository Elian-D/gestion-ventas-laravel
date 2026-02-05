<?php

namespace Database\Seeders\AccountingSeeders;

use App\Models\Accounting\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $docs = [
            [
                'name' => 'Factura de Venta',
                'code' => 'FAC',
                'prefix' => 'FAC',
                'current_number' => 0,
            ],
            [
                'name' => 'Recibo de Ingreso',
                'code' => 'REC',
                'prefix' => 'REC',
                'current_number' => 0,
            ],
            [
                'name' => 'Nota de Crédito',
                'code' => 'NC',
                'prefix' => 'NC',
                'current_number' => 0,
            ],
            [
                'name' => 'Asiento Manual',
                'code' => 'MAN',
                'prefix' => 'AS',
                'current_number' => 0,
            ],
            [
                'name' => 'Comprobante de Pago',
                'code' => 'PAG',
                'prefix' => 'PAG',
                'current_number' => 0,
            ],
        ];

        foreach ($docs as $doc) {
            DocumentType::updateOrCreate(['code' => $doc['code']], $doc);
        }
    }
}