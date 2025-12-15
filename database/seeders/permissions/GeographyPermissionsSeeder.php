<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class GeographyPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = ['provincias', 'municipios', 'sectores'];
        $acciones = ['index', 'create', 'edit', 'delete'];

        foreach ($modulos as $modulo) {
            foreach ($acciones as $accion) {
                Permission::firstOrCreate(['name' => "$modulo $accion"]);
            }
        }
    }
}
