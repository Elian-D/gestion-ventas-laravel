<?php

namespace Database\Seeders;

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
                'estado' => true, // La fila está activa
                'clase_fondo' => 'bg-green-100',
                'clase_texto' => 'text-green-800',
            ],
            [
                'nombre' => 'Inactivo',
                'estado' => true, // La fila está inactiva/desactivada
                'clase_fondo' => 'bg-gray-100',
                'clase_texto' => 'text-gray-800',
            ],
            [
                'nombre' => 'Suspendido',
                'estado' => true, 
                'clase_fondo' => 'bg-yellow-100',
                'clase_texto' => 'text-yellow-800',
            ],
            [
                'nombre' => 'Moroso',
                'estado' => true,
                'clase_fondo' => 'bg-red-100',
                'clase_texto' => 'text-red-800',
            ],
            [
                'nombre' => 'Prospecto',
                'estado' => true,
                'clase_fondo' => 'bg-blue-100', // Usando blue para prospectos
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