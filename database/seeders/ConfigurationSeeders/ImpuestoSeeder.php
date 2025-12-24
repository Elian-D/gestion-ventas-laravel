<?php

namespace Database\Seeders\ConfigurationSeeders;

use Illuminate\Database\Seeder;
use App\Models\Configuration\Impuesto;

class ImpuestoSeeder extends Seeder
{
    public function run(): void
    {
        Impuesto::updateOrCreate(
            ['id' => 1],
            [
                'nombre' => 'Impuesto General',
                'tipo' => Impuesto::TIPO_PORCENTAJE,
                'valor' => 0,
                'es_incluido' => false,
            ]
        );
    }
}
