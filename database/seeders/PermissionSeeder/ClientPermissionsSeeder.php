<?php

namespace Database\Seeders\PermissionSeeder;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ClientPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'configure business types', // Gestionar tipo de negocios
            'configure equipment types', // Gestionar tipo de equipos
            'clients index',    // Ver listado
            'clients create',   // Crear nuevos
            'clients edit',     // Editar y activar/desactivar
            'clients delete',   // Borrar y purgar papelera
            'clients restore',  // Ver papelera y restaurar
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
