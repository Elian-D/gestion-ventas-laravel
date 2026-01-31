<?php

namespace Database\Seeders\ProductsSeeders;

use App\Models\Products\Product;
use App\Models\Products\Category;
use App\Models\Products\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtener los IDs necesarios
        $catHielo = Category::where('name', 'Hielo')->first()->id;
        $unitFunda = Unit::where('abbreviation', 'fnd')->first()->id;
        $unitLibra = Unit::where('abbreviation', 'lb')->first()->id;

        $products = [
            [
                'category_id'  => $catHielo,
                'unit_id'      => $unitFunda,
                'name'         => 'Funda de Hielo 10lb',
                'sku'          => 'PRD-00001',
                'description'  => 'Funda de hielo cristalino de 10 libras.',
                'price'        => 150.00,
                'cost'         => 45.00,
                'stock'        => 100,
                'min_stock'    => 20,
                'is_active'    => true,
                'is_stockable' => true,
            ],
            [
                'category_id'  => $catHielo,
                'unit_id'      => $unitLibra,
                'name'         => 'Hielo al granel (Libra)',
                'sku'          => 'PRD-00002',
                'description'  => 'Venta de hielo por peso para eventos.',
                'price'        => 15.00,
                'cost'         => 5.00,
                'stock'        => 500,
                'min_stock'    => 50,
                'is_active'    => true,
                'is_stockable' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']], // Buscamos por SKU para no duplicar
                array_merge($product, ['slug' => Str::slug($product['name'])])
            );
        }
    }
}