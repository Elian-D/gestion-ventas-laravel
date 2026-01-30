<?php

namespace Database\Seeders\PermissionSeeder;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ProductsPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'configure categories', // Gestionar tipo de negocios
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
