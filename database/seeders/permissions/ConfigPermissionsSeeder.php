<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ConfigPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'view configuration']);
        Permission::firstOrCreate(['name' => 'configure documents']);
        Permission::firstOrCreate(['name' => 'configure client-states']);
    }
}
