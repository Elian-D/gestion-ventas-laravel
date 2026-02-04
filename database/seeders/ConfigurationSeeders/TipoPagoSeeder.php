<?php

namespace Database\Seeders\ConfigurationSeeders;

use App\Models\Configuration\TipoPago;
use Illuminate\Database\Seeder;

class TipoPagoSeeder extends Seeder
{
    public function run(): void
    {
        $tiposPago = [
            ['nombre' => 'Efectivo'],
            ['nombre' => 'Transferencia Bancaria'],
            ['nombre' => 'Cheque'],
            ['nombre' => 'Tarjeta de Crédito/Débito'],
            ['nombre' => 'Depósito Bancario'],
            ['nombre' => 'Nota de Crédito Aplicada'], // Útil para cruces contables
        ];

        foreach ($tiposPago as $tipoPago) {
            TipoPago::updateOrCreate(
                ['nombre' => $tipoPago['nombre']],
                ['estado' => true]
            );
        }
    }
}