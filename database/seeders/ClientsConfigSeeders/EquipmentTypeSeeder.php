<?php

namespace Database\Seeders\ClientsConfigSeeders;

use App\Models\Clients\EquipmentType; // Asegúrate de que el path del modelo sea correcto
use Illuminate\Database\Seeder;

class EquipmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposEquipos = [
            'Freezer',
            'Anaquel',
            'Refrigerador',
            'Exhibidor Vertical',
            'Cava Cuarto',
            'Mostrador',
            'Góndola Central'
        ];

        foreach ($tiposEquipos as $nombre) {
            EquipmentType::updateOrCreate(
                ['nombre' => $nombre], // Busca por nombre para evitar duplicados
                ['activo' => true]     // Lo asegura como activo
            );
        }
    }
}