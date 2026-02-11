<?php
namespace Database\Seeders\SalesSeeders;

use App\Models\Sales\Ncf\NcfType;
use Illuminate\Database\Seeder;

class NcfTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            // --- COMPROBANTES ESTÁNDAR (Serie B) ---
            ['name' => 'Crédito Fiscal', 'prefix' => 'B', 'code' => '01', 'requires_rnc' => true, 'is_electronic' => false],
            ['name' => 'Consumo', 'prefix' => 'B', 'code' => '02', 'requires_rnc' => false, 'is_electronic' => false],
            ['name' => 'Notas de Débito', 'prefix' => 'B', 'code' => '03', 'requires_rnc' => true, 'is_electronic' => false],
            ['name' => 'Notas de Crédito', 'prefix' => 'B', 'code' => '04', 'requires_rnc' => true, 'is_electronic' => false],
            ['name' => 'Regímenes Especiales', 'prefix' => 'B', 'code' => '14', 'requires_rnc' => true, 'is_electronic' => false],
            ['name' => 'Gubernamental', 'prefix' => 'B', 'code' => '15', 'requires_rnc' => true, 'is_electronic' => false],

            // --- COMPROBANTES ELECTRÓNICOS (Serie E) ---
            ['name' => 'Factura de Crédito Fiscal Electrónica', 'prefix' => 'E', 'code' => '31', 'requires_rnc' => true, 'is_electronic' => true],
            ['name' => 'Factura de Consumo Electrónica', 'prefix' => 'E', 'code' => '32', 'requires_rnc' => false, 'is_electronic' => true],
            ['name' => 'Nota de Débito Electrónica', 'prefix' => 'E', 'code' => '33', 'requires_rnc' => true, 'is_electronic' => true],
            ['name' => 'Nota de Crédito Electrónica', 'prefix' => 'E', 'code' => '34', 'requires_rnc' => true, 'is_electronic' => true],
            ['name' => 'Regímenes Especiales Electrónica', 'prefix' => 'E', 'code' => '44', 'requires_rnc' => true, 'is_electronic' => true],
            ['name' => 'Gubernamental Electrónica', 'prefix' => 'E', 'code' => '45', 'requires_rnc' => true, 'is_electronic' => true],
        ];

        foreach ($types as $type) {
            NcfType::updateOrCreate(
                ['code' => $type['code'], 'prefix' => $type['prefix']], 
                $type
            );
        }
    }
}