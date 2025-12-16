<?php

namespace Database\Seeders\ConfigurationSeeders;

use Illuminate\Database\Seeder;
use App\Models\Configuration\Moneda;

class MonedaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        // Todas las monedas se crean como NO principales.
        // La moneda principal será definida en ConfiguracionGeneralSeeder.

        $monedas = [
            [
                'nombre' => 'Dólar estadounidense',
                'codigo' => 'USD',
                'simbolo' => '$',
                'decimales' => 2,
                'es_principal' => false,
            ],
            [
                'nombre' => 'Euro',
                'codigo' => 'EUR',
                'simbolo' => '€',
                'decimales' => 2,
                'es_principal' => false,
            ],
            [
                'nombre' => 'Peso dominicano',
                'codigo' => 'DOP',
                'simbolo' => 'RD$',
                'decimales' => 2,
                'es_principal' => false,
            ],
        ];

        foreach ($monedas as $moneda) {
            Moneda::updateOrCreate(
                ['codigo' => $moneda['codigo']],
                $moneda
            );
        }
    }
}
