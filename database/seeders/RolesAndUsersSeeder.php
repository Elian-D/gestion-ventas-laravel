<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de roles y permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // PERMISOS
        $viewDashboard = Permission::firstOrCreate(['name' => 'view dashboard']);
        $rolesIndex = Permission::firstOrCreate(['name' => 'roles index']);
        $rolesCreate = Permission::firstOrCreate(['name' => 'roles create']);
        $rolesEdit = Permission::firstOrCreate(['name' => 'roles edit']);
        $rolesDelete = Permission::firstOrCreate(['name' => 'roles delete']);
        $rolesAssign = Permission::firstOrCreate(['name' => 'roles assign']);
        // ROLES
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $normalRole = Role::firstOrCreate(['name' => 'normal']);

        // Asignar permisos al rol admin
        $adminRole->syncPermissions([
            $viewDashboard,
            $rolesIndex,
            $rolesCreate,
            $rolesEdit,
            $rolesDelete,
            $rolesAssign,
        ]);

        // Asignar permisos al rol normal (solo ver dashboard)
        $normalRole->syncPermissions([
            $viewDashboard,
        ]);

        // USUARIO ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@local.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('12345678'), // cámbialo si quieres
            ]
        );
        $admin->assignRole('admin');

        // USUARIO NORMAL
        $normal = User::firstOrCreate(
            ['email' => 'usuario@local.com'],
            [
                'name' => 'Usuario Normal',
                'password' => Hash::make('12345678'),
            ]
        );
        $normal->assignRole('normal');
    }
}
