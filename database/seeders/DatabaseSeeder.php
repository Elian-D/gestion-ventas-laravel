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
            // Permission Seeders
            \Database\Seeders\PermissionSeeder\DashboardPermissionsSeeder::class,
            \Database\Seeders\PermissionSeeder\RolesPermissionsSeeder::class,
            \Database\Seeders\PermissionSeeder\UsersPermissionsSeeder::class,
            \Database\Seeders\PermissionSeeder\ConfigPermissionsSeeder::class,
            \Database\Seeders\PermissionSeeder\GeographyPermissionsSeeder::class,
            \Database\Seeders\PermissionSeeder\ClientPermissionsSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,

            // Configuration Seeders
            \Database\Seeders\ConfigurationSeeders\EstadosClienteSeeder::class,
            \Database\Seeders\ConfigurationSeeders\DiaSemanaSeeder::class,
            \Database\Seeders\ConfigurationSeeders\TipoPagoSeeder::class,
            \Database\Seeders\ConfigurationSeeders\ImpuestoSeeder::class,
            \Database\Seeders\ConfigurationSeeders\ConfiguracionGeneralSeeder::class,
            \Database\Seeders\ConfigurationSeeders\TaxIdentifierTypeSeeder::class,

            // Clients Configuration Seeders
            \Database\Seeders\ClientsConfigSeeders\BusinessTypeSeeder::class,
            \Database\Seeders\ClientsConfigSeeders\EquipmentTypeSeeder::class,
            \Database\Seeders\ClientsConfigSeeders\ClientSeeder::class,
        ]);
    }
}
