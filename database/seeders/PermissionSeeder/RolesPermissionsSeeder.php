<?php

namespace Database\Seeders\PermissionSeeder;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'roles index']);
        Permission::firstOrCreate(['name' => 'roles create']);
        Permission::firstOrCreate(['name' => 'roles edit']);
        Permission::firstOrCreate(['name' => 'roles delete']);
        Permission::firstOrCreate(['name' => 'roles assign']);
    }
}
