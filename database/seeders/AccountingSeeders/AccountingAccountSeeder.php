<?php

namespace Database\Seeders\AccountingSeeders;

use App\Models\Accounting\AccountingAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountingAccountSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            // ACTIVOS
            ['code' => '1', 'name' => 'Activos', 'type' => AccountingAccount::TYPE_ASSET, 'is_selectable' => false, 'level' => 1],
            ['code' => '1.1', 'name' => 'Activos Corrientes', 'type' => AccountingAccount::TYPE_ASSET, 'is_selectable' => false, 'level' => 2, 'parent_code' => '1'],
            ['code' => '1.1.01', 'name' => 'Caja y Bancos', 'type' => AccountingAccount::TYPE_ASSET, 'is_selectable' => true, 'level' => 3, 'parent_code' => '1.1'],
            ['code' => '1.1.02', 'name' => 'Cuentas por Cobrar', 'type' => AccountingAccount::TYPE_ASSET, 'is_selectable' => true, 'level' => 3, 'parent_code' => '1.1'],
            ['code' => '1.1.03', 'name' => 'Inventarios', 'type' => AccountingAccount::TYPE_ASSET, 'is_selectable' => true, 'level' => 3, 'parent_code' => '1.1'],
            
            // INGRESOS
            ['code' => '4', 'name' => 'Ingresos', 'type' => AccountingAccount::TYPE_REVENUE, 'is_selectable' => false, 'level' => 1],
            ['code' => '4.1', 'name' => 'Ventas de Productos', 'type' => AccountingAccount::TYPE_REVENUE, 'is_selectable' => true, 'level' => 2, 'parent_code' => '4'],
            
            // COSTOS
            ['code' => '5', 'name' => 'Costos', 'type' => AccountingAccount::TYPE_COST, 'is_selectable' => false, 'level' => 1],
            ['code' => '5.1', 'name' => 'Costo de Ventas', 'type' => AccountingAccount::TYPE_COST, 'is_selectable' => true, 'level' => 2, 'parent_code' => '5'],
        ];

        foreach ($accounts as $acc) {
            $parentId = isset($acc['parent_code']) 
                ? DB::table('accounting_accounts')->where('code', $acc['parent_code'])->value('id') 
                : null;

            DB::table('accounting_accounts')->insert([
                'parent_id' => $parentId,
                'code' => $acc['code'],
                'name' => $acc['name'],
                'type' => $acc['type'],
                'level' => $acc['level'],
                'is_selectable' => $acc['is_selectable'],
                'created_at' => now(),
            ]);
        }
    }
}
