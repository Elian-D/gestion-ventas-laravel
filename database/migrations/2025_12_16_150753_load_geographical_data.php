<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;



return new class extends Migration
{

    public function up()
    {
        // Aumentar el tiempo límite de ejecución si el SQL es muy pesado
        set_time_limit(300);

        // Lista de archivos en el orden que deben cargarse (por las llaves foráneas)
        $files = [
            'countries.sql',
            'regions.sql',
            'states.sql',
            'subregions.sql'
        ];

        foreach ($files as $filename) {
            $path = database_path("geo/{$filename}");
            
            if (File::exists($path)) {
                // Ejecuta el contenido completo del archivo
                DB::unprepared(File::get($path));
            }
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subregions');
        Schema::dropIfExists('states');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('countries');
    }
};
