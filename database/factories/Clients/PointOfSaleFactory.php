<?php

namespace Database\Factories\Clients;

use App\Models\Clients\Client;
use App\Models\Clients\PointOfSale;
use App\Models\Clients\BusinessType;
use App\Models\Geo\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class PointOfSaleFactory extends Factory
{
    protected $model = PointOfSale::class;
    protected int $countryId;

    public function __construct(...$args)
    {
        parent::__construct(...$args);
        $this->countryId = general_config()->country_id;
    }

    public function definition(): array
    {
        return [
            'client_id' => Client::query()->inRandomOrder()->value('id') ?? Client::factory(),
            'business_type_id' => BusinessType::query()->inRandomOrder()->value('id') ?? 1,
            'name' => fake()->company() . ' - ' . fake()->city(),
            // 'code' se queda fuera para que lo maneje el hook
            'state_id' => State::query()->where('country_id', $this->countryId)->inRandomOrder()->value('id') ?? 1,
            'city' => fake()->city(),
            'address' => fake()->address(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'contact_name' => fake()->name(),
            'contact_phone' => fake()->phoneNumber(),
            'active' => fake()->boolean(90),
        ];
    }

    /**
     * Hook para disparar la generación del código justo después de crear
     */
    public function configure()
    {
        return $this->afterCreating(function (PointOfSale $pos) {
            $prefix = $pos->businessType->prefix ?? 'POS';
            
            $pos->updateQuietly([
                'code' => sprintf('%s-%05d', strtoupper($prefix), $pos->id)
            ]);
        });
    }
}