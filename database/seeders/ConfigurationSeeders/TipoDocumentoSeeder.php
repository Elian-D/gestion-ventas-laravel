<?php

namespace Database\Seeders\ConfigurationSeeders;

use App\Models\Configuration\TipoDocumento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentos = [
            [
                'nombre' => 'Cédula',
                'estado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'RNC',
                'estado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'Pasaporte',
                'estado' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($documentos as $documento) {
            // Se usa updateOrCreate para evitar duplicados si se ejecuta el seeder varias veces
            TipoDocumento::updateOrCreate(
                ['nombre' => $documento['nombre']],
                $documento
            );
        }
    }
}
