<?php

namespace Database\Seeders\PermissionSeeder;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UsersPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'users index']);
        Permission::firstOrCreate(['name' => 'users create']);
        Permission::firstOrCreate(['name' => 'users edit']);
        Permission::firstOrCreate(['name' => 'users delete']);
        Permission::firstOrCreate(['name' => 'users assign']);
    }
}
