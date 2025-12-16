<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            \Database\Seeders\Permissions\DashboardPermissionsSeeder::class,
            \Database\Seeders\Permissions\RolesPermissionsSeeder::class,
            \Database\Seeders\Permissions\UsersPermissionsSeeder::class,
            \Database\Seeders\Permissions\ConfigPermissionsSeeder::class,
            \Database\Seeders\Permissions\GeographyPermissionsSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            EstadosClienteSeeder::class,
            TipoDocumentoSeeder::class,
        ]);
    }
}
