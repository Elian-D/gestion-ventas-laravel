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

            'view document types',
            'create document types',
            'edit document types',
            'delete document types',

            'view receivables',
            'create receivables', // Para deudas manuales/ajustes
            'edit receivables',
            'cancel receivables',
            'report receivables', // Para ver reportes de antigüedad de saldos

            'view payments',
            'create payments',
            'edit payments',
            'cancel payments', // Importante para reversiones contables
            'delete payments', // SoftDelete
            'export payments',
            'print payment receipts', // Específico para el PDF

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
