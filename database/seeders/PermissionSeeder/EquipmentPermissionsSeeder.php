<?php

namespace Database\Seeders\PermissionSeeder;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class EquipmentPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Listado y visualización
            'equipment index',

            // Creación
            'equipment create',

            // Edición y acciones masivas
            'equipment edit',

            // Eliminación lógica y definitiva
            'equipment delete',

            // Papelera y restauración
            'equipment restore',

            // Acción sensible: regenerar código
            'equipment regenerate-code',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
