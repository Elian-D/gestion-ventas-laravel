<?php

namespace Database\Seeders\ClientsConfigSeeders;

use App\Models\Clients\Client;
use App\Models\Accounting\AccountingAccount;
use App\Models\Configuration\EstadosCliente;
use App\Models\Configuration\TaxIdentifierType;
use App\Models\Geo\State;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $countryId = function_exists('general_config') ? general_config()->country_id : 1;

        // 1. Crear el Cliente Genérico (Consumidor Final)
        Client::firstOrCreate(
            ['tax_id' => '00000000000'], // Identificador genérico
            [
                'type' => 'individual',
                'estado_cliente_id' => EstadosCliente::where('nombre', 'Activo')->value('id') ?? 1,
                'name' => 'Consumidor Final',
                'email' => 'consumidor@final.com',
                'phone' => '0000000000',
                'state_id' => State::where('country_id', $countryId)->value('id') ?? 1,
                'city' => 'N/A',
                'address' => 'Ventas de Mostrador',
                'tax_identifier_type_id' => TaxIdentifierType::where('country_id', $countryId)->value('id') ?? 1,
                'credit_limit' => 0, // No tiene crédito
                'balance' => 0,
                'payment_terms' => 0,
                'accounting_account_id' => null
            ]
        );

        // 2. Crear los 30 clientes aleatorios mediante Factory
        Client::factory()->count(30)->create();
    }
}