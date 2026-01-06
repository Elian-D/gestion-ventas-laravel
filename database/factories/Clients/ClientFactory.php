<?php

namespace Database\Factories\Clients;

use App\Models\Configuration\EstadosCliente;
use App\Models\Configuration\TaxIdentifierType;
use App\Models\Geo\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    // 50% de probabilidad para el tipo
    $type = fake()->boolean(50) ? 'individual' : 'company';

    return [
        'type' => $type,
        // Asumiendo que tienes registros en estas tablas, tomamos uno al azar
        'estado_cliente_id' => EstadosCliente::inRandomOrder()->first()->id ?? 1,
        
        'name' => ($type === 'individual') ? fake()->name() : fake()->company(),
        'commercial_name' => ($type === 'company') ? fake()->companySuffix() : null,
        
        'email' => fake()->unique()->safeEmail(),
        'phone' => fake()->phoneNumber(),
        
        // Relación con tablas geográficas existentes
        'state_id' => State::inRandomOrder()->first()->id ?? 1,
        'city' => fake()->city(),
        
        // Identificación fiscal
        'tax_identifier_type_id' => TaxIdentifierType::inRandomOrder()->first()->id ?? 1,
        'tax_id' => ($type === 'individual') ? fake()->numerify('###########') : fake()->numerify('#########'),
        
        // 75% de probabilidad de ser verdadero (activo)
        'active' => fake()->boolean(75),
    ];
}

}
