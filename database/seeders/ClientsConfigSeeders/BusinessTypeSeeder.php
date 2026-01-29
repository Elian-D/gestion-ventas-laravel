<?php

namespace Database\Seeders\ClientsConfigSeeders;

use Illuminate\Database\Seeder;
use App\Models\Clients\BusinessType;

class BusinessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposNegocio = [
            'Restaurante / Gastronomía',
            'Tienda de Ropa / Retail',
            'Servicios Profesionales',
            'Salud y Bienestar',
            'Educación / Academias',
            'Construcción y Ferretería',
            'Tecnología y Software',
            'Transporte y Logística',
            'Turismo y Hotelería',
            'Supermercado / Abarrotes'
        ];


        foreach ($tiposNegocio as $nombre) {
            $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $nombre), 0, 3));

            BusinessType::updateOrCreate(
                ['nombre' => $nombre],
                [
                    'activo' => true,
                    'prefix' => $prefix
                ]
            );
        }
    }
}