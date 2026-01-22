<?php

namespace Database\Seeders\ConfigurationSeeders;

use Illuminate\Database\Seeder;
use App\Models\Configuration\ClientStateCategory;

class ClientStateCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'code' => 'OPERATIVO',
                'name' => 'Operación Normal',
                'description' => 'Cliente sano. Acceso total a todas las funciones.',
            ],
            [
                'code' => 'PRE_CLIENTE',
                'name' => 'Prospecto / Registro',
                'description' => 'Cliente registrado sin historial operativo.',
            ],
            [
                'code' => 'FINANCIERO_RESTRICTO',
                'name' => 'Restricción Financiera',
                'description' => 'Puede interactuar, pero no facturar ni despachar.',
            ],
            [
                'code' => 'BLOQUEO_TOTAL',
                'name' => 'Inactivo / Bloqueado',
                'description' => 'Relación pausada o terminada.',
            ],
            [
                'code' => 'RIESGO',
                'name' => 'Riesgo / Observación',
                'description' => 'Operativo con advertencias (ej. solo contado).',
            ],
        ];

        foreach ($categories as $category) {
            ClientStateCategory::updateOrCreate(
                ['code' => $category['code']],
                $category
            );
        }
    }
}
