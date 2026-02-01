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

            'view journal entries',
            'create journal entries',
            'edit journal entries',
            'post journal entries', // Permiso especial para "Asentar"
            'cancel journal entries',
            'delete journal entries',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
