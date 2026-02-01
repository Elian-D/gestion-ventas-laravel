<?php

namespace Database\Seeders\PermissionSeeder;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AccountingPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'configure accounting',
            'configure accounting account',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
