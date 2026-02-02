<?php

namespace Database\Factories\Clients;

use App\Models\Configuration\EstadosCliente;
use App\Models\Configuration\TaxIdentifierType;
use App\Models\Geo\State;
use App\Models\Accounting\AccountingAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected int $countryId;

    public function __construct(...$args)
    {
        parent::__construct(...$args);
        $this->countryId = general_config()->country_id;
    }

    public function definition(): array
    {
        $type = fake()->boolean(50) ? 'individual' : 'company';
        
        // Lógica financiera dinámica
        $hasCredit = fake()->boolean(40); // 40% tiene crédito
        $creditLimit = $hasCredit ? fake()->randomElement([5000, 10000, 25000, 50000, 100000]) : 0;
        
        // Solo asignar cuenta contable propia si el límite es "alto" (ej. > 20,000)
        $needsCustomAccount = $creditLimit > 20000;

        return [
            'type' => $type,
            'estado_cliente_id' => EstadosCliente::query()->inRandomOrder()->value('id') ?? 1,
            'name' => $type === 'individual' ? fake()->name() : fake()->company(),
            'commercial_name' => $type === 'company' ? fake()->companySuffix() : null,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'state_id' => State::query()->where('country_id', $this->countryId)->inRandomOrder()->value('id') ?? 1,
            'city' => fake()->city(),
            'address' => fake()->address(),
            'tax_identifier_type_id' => TaxIdentifierType::query()->where('country_id', $this->countryId)->inRandomOrder()->value('id') ?? 1,
            'tax_id' => $type === 'individual' ? fake()->numerify('###########') : fake()->numerify('#########'),
            
            // Campos Financieros
            'credit_limit' => $creditLimit,
            'balance' => $hasCredit ? fake()->randomFloat(2, 0, $creditLimit * 0.8) : 0, // Deuda inicial aleatoria
            'payment_terms' => $hasCredit ? fake()->randomElement([15, 30, 45]) : 0,
            'accounting_account_id' => $needsCustomAccount 
                ? AccountingAccount::query()->where('code', 'like', '1.1.02%')->inRandomOrder()->value('id') 
                : null,
        ];
    }
}