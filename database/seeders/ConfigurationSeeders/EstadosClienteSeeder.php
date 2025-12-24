<?php

namespace Database\Seeders\ConfigurationSeeders;


use App\Models\Configuration\EstadosCliente; // Asegúrate de importar tu modelo
use Illuminate\Database\Seeder;

class EstadosClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $estados = [
            [
                'nombre' => 'Activo',
                'activo' => true,
                'permite_operar' => true,
                'permite_facturar' => true,
                'clase_fondo' => 'bg-green-100',
                'clase_texto' => 'text-green-800',
            ],
            [
                'nombre' => 'Inactivo',
                'activo' => true,
                'permite_operar' => false,
                'permite_facturar' => false,
                'clase_fondo' => 'bg-gray-100',
                'clase_texto' => 'text-gray-800',
            ],
            [
                'nombre' => 'Suspendido',
                'activo' => true,
                'permite_operar' => false,
                'permite_facturar' => false,
                'clase_fondo' => 'bg-yellow-100',
                'clase_texto' => 'text-yellow-800',
            ],
            [
                'nombre' => 'Moroso',
                'activo' => true,
                'permite_operar' => true,
                'permite_facturar' => false,
                'clase_fondo' => 'bg-red-100',
                'clase_texto' => 'text-red-800',
            ],
            [
                'nombre' => 'Prospecto',
                'activo' => true,
                'permite_operar' => false,
                'permite_facturar' => false,
                'clase_fondo' => 'bg-blue-100',
                'clase_texto' => 'text-blue-800',
            ],
        ];


        foreach ($estados as $estado) {
            // Se usa updateOrCreate para evitar duplicados si se ejecuta el seeder varias veces
            EstadosCliente::updateOrCreate(
                ['nombre' => $estado['nombre']],
                $estado
            );
        }
    }
}