<?php

namespace Database\Seeders\ConfigurationSeeders;

use App\Models\Configuration\TipoPago;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoPagoSeeder extends Seeder
{
    public function run(): void
    {
        $cajaId = DB::table('accounting_accounts')->where('code', '1.1.01')->value('id');

        $tiposPago = [
            ['nombre' => 'Efectivo', 'account_id' => $cajaId],
            ['nombre' => 'Transferencia Bancaria', 'account_id' => $cajaId],
            ['nombre' => 'Cheque', 'account_id' => $cajaId],
            ['nombre' => 'Tarjeta de Crédito/Débito', 'account_id' => $cajaId],
            ['nombre' => 'Depósito Bancario', 'account_id' => $cajaId],
            ['nombre' => 'Nota de Crédito Aplicada', 'account_id' => null], 
        ];

        foreach ($tiposPago as $tipo) {
            TipoPago::updateOrCreate(
                ['nombre' => $tipo['nombre']],
                [
                    'estado' => true,
                    'accounting_account_id' => $tipo['account_id']
                ]
            );
        }
    }
}