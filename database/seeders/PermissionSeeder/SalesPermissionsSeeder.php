<?php

namespace Database\Seeders\PermissionSeeder;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SalesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Gestión General de Ventas (Vista Administrativa)
            'view sales',
            'create sales',
            'edit sales',
            'cancel sales', // En lugar de borrar, se anula para trazabilidad
            'delete sales', // SoftDelete por seguridad técnica
            'export sales',
            'print sales receipts', // Para el PDF del recibo o factura

            'view invoices',
            'export invoices',
            'print invoices',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}