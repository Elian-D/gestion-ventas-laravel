<?php

namespace Database\Seeders\ConfigurationSeeders;

use Illuminate\Database\Seeder;
use App\Models\Configuration\Impuesto;

class ImpuestoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ITBIS - República Dominicana
        |--------------------------------------------------------------------------
        */
        Impuesto::updateOrCreate(
            ['nombre' => 'ITBIS'],
            [
                'tipo'        => Impuesto::TIPO_PORCENTAJE,
                'valor'       => 18.00,
                'es_incluido' => false,
                'estado'      => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Otros impuestos, descomentar según país
        |--------------------------------------------------------------------------
        */

        // Impuesto::updateOrCreate(
        //     ['nombre' => 'IVA'],
        //     [
        //         'tipo'        => Impuesto::TIPO_PORCENTAJE,
        //         'valor'       => 16.00,
        //         'es_incluido' => false,
        //         'estado'      => true,
        //     ]
        // );

        // Impuesto::updateOrCreate(
        //     ['nombre' => 'ISR'],
        //     [
        //         'tipo'        => Impuesto::TIPO_PORCENTAJE,
        //         'valor'       => 10.00,
        //         'es_incluido' => false,
        //         'estado'      => true,
        //     ]
        // );

        // Impuesto::updateOrCreate(
        //     ['nombre' => 'Impuesto Fijo Municipal'],
        //     [
        //         'tipo'        => Impuesto::TIPO_FIJO,
        //         'valor'       => 50.00,
        //         'es_incluido' => false,
        //         'estado'      => true,
        //     ]
        // );
    }
}
