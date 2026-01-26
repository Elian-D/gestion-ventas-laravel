<?php

namespace Database\Seeders\PermissionSeeder;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class POSPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'pos index',    // Ver listado de puntos de venta
            'pos create',   // Crear nuevos PDV
            'pos edit',     // Editar y realizar acciones masivas
            'pos delete',   // Mover a papelera y eliminación definitiva
            'pos restore',  // Ver papelera y restaurar registros
            'pos regenerate-code' // Regenerar código de punto de venta, solo para usuarios autorizados
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}