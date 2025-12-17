<?php

namespace Database\Seeders\ConfigurationSeeders;

use App\Models\Configuration\ConfiguracionGeneral;
use App\Models\Configuration\Impuesto;
use App\Models\Configuration\Moneda;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfiguracionGeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $moneda = Moneda::where('codigo', 'DOP')->firstOrFail();
        $impuesto = Impuesto::where('nombre', 'ITBIS')->firstOrFail();

        ConfiguracionGeneral::updateOrCreate(
            ['id' => 1],
            [
                'nombre_empresa' => 'Mi Empresa',
                'moneda_id' => $moneda->id,
                'impuesto_id' => $impuesto->id,
                'timezone' => 'America/Santo_Domingo',
            ]
);

    }
}
