<?php

namespace Database\Seeders\PermissionSeeder;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class InventoryPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view inventory dashboard',

            'configure warehouses', // Gestionar almacenes
            'inventory stocks index', // Ver balance de productos por alamacén
            'inventory stocks update', // Editar el min_stock cuando sea necesario
            'inventory stocks export', // Exportar valores
            'view inventory movements', // Ver el historial (Kardex)
            'create inventory adjustments', // Realizar ajustes manuales de stock
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
