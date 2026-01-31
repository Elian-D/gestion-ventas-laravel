<?php

namespace Database\Seeders\PermissionSeeder;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ProductsPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'configure categories', // Gestionar categorías de productos
            'configure units', // Gestionar unidades de medida
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
