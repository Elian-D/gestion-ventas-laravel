<?php

namespace Database\Seeders\ConfigurationSeeders;

use App\Models\Configuration\ConfiguracionGeneral;
use App\Models\Geo\Country;
use Illuminate\Database\Seeder;

class ConfiguracionGeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * País base del sistema
         * 👉 Cambiar ISO2 según el país del cliente (MX, CO, PE, etc.)
         */
        $country = Country::where('iso2', 'DO')->first();

        if (!$country) {
            throw new \RuntimeException('País base no encontrado en la base de datos.');
        }

        /**
         * Resolver timezone
         * - Si el país tiene varias zonas, se toma la primera
         * - Si no hay info válida, fallback al app.timezone
         */
        $timezone = config('app.timezone');

        if (!empty($country->timezones)) {
            $zones = json_decode($country->timezones, true);

            if (is_array($zones) && isset($zones[0]['zoneName'])) {
                $timezone = $zones[0]['zoneName'];
            }
        }

        ConfiguracionGeneral::updateOrCreate(
            ['id' => 1],
            [
                'nombre_empresa'   => 'Empresa Demo',
                'country_id'       => $country->id,
                'state_id'         => null, // Se define luego desde el panel
                'ciudad'           => null,

                // Moneda sugerida por país (editable luego)
                'impuesto_id' => 1,
                'currency'         => $country->currency,
                'currency_name'    => $country->currency_name,
                'currency_symbol'  => $country->currency_symbol,

                // Zona horaria automática
                'timezone'         => $timezone,
            ]
        );
    }
}
