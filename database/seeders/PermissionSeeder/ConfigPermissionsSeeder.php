<?php

namespace Database\Seeders\PermissionSeeder;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ConfigPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'view configuration']);
        Permission::firstOrCreate(['name' => 'configure general data']);;
        Permission::firstOrCreate(['name' => 'configure payments']);
        Permission::firstOrCreate(['name' => 'configure client-states']);
        Permission::firstOrCreate(['name' => 'configure dias-semana']);
    }
}
