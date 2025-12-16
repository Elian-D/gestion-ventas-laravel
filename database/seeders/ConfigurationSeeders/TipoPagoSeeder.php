<?php

namespace Database\Seeders\ConfigurationSeeders;

use App\Models\Configuration\TipoPago;
use Illuminate\Database\Seeder;

class TipoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposPago = [
            [
                'nombre' => 'Efectivo',
                'estado' => true,
            ],
            [
                'nombre' => 'Cheque',
                'estado' => true,
            ],
            [
                'nombre' => 'Transferencia',
                'estado' => true,
            ],
            [
                'nombre' => 'Tarjeta',
                'estado' => true,
            ],
            [
                'nombre' => 'Apple Pay',
                'estado' => true,
            ],
            [
                'nombre' => 'Google Pay',
                'estado' => true,
            ],
        ];

        foreach ($tiposPago as $tipoPago) {
            TipoPago::updateOrCreate(
                ['nombre' => $tipoPago['nombre']],
                ['estado' => true]
            );
        }
    }
}
