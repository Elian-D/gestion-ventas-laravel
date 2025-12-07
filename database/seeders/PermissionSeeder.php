<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PERMISOS Dashboard
        $viewDashboard = Permission::firstOrCreate(['name' => 'view dashboard']);

        // PERMISOS Roles
        $rolesIndex = Permission::firstOrCreate(['name' => 'roles index']);
        $rolesCreate = Permission::firstOrCreate(['name' => 'roles create']);
        $rolesEdit = Permission::firstOrCreate(['name' => 'roles edit']);
        $rolesDelete = Permission::firstOrCreate(['name' => 'roles delete']);
        $rolesAssign = Permission::firstOrCreate(['name' => 'roles assign']);
        
        // PERMISOS Roles
        $usersIndex = Permission::firstOrCreate(['name' => 'users index']);
        $usersCreate = Permission::firstOrCreate(['name' => 'users create']);
        $usersEdit = Permission::firstOrCreate(['name' => 'users edit']);
        $usersDelete = Permission::firstOrCreate(['name' => 'users delete']);
        $usersAssign = Permission::firstOrCreate(['name' => 'users assign']);
    }
}
