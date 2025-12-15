<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DashboardPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'view dashboard']);
    }
}
