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

            'view products',
            'create products',
            'edit products',
            'delete products',
            'restore products',
            'manage stock' // Permiso especial para el futuro módulo de inventario
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
