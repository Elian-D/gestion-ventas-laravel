<?php

namespace Database\Factories\Clients;

use App\Models\Configuration\EstadosCliente;
use App\Models\Configuration\TaxIdentifierType;
use App\Models\Geo\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected int $countryId;

    public function __construct(...$args)
    {
        parent::__construct(...$args);

        // Helper global
        $this->countryId = general_config()->country_id;
    }

    public function definition(): array
    {
        // 50% de probabilidad
        $type = fake()->boolean(50) ? 'individual' : 'company';

        return [
            'type' => $type,

            'estado_cliente_id' => EstadosCliente::query()
                ->inRandomOrder()
                ->value('id') ?? 1,

            'name' => $type === 'individual'
                ? fake()->name()
                : fake()->company(),

            'commercial_name' => $type === 'company'
                ? fake()->companySuffix()
                : null,

            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),

            // 👇 Usando helper para filtrar por país
            'state_id' => State::query()
                ->where('country_id', $this->countryId)
                ->inRandomOrder()
                ->value('id') ?? 1,

            'city' => fake()->city(),

            // 👇 Usando helper para filtrar por país
            'tax_identifier_type_id' => TaxIdentifierType::query()
                ->where('country_id', $this->countryId)
                ->inRandomOrder()
                ->value('id') ?? 1,

            'tax_id' => $type === 'individual'
                ? fake()->numerify('###########')
                : fake()->numerify('#########'),
        ];
    }
}
