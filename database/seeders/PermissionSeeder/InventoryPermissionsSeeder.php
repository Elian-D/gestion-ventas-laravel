<?php

namespace Database\Seeders\PermissionSeeder;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class InventoryPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'configure warehouses', // Gestionar almacenes
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
