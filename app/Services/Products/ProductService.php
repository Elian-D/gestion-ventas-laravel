<?php

namespace App\Services\Products;

use App\Models\Products\Product;
use App\Traits\HandleStorage; // 1. Importar el Trait
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    use HandleStorage; // 2. "Pegar" las habilidades del Trait

    public function createProduct(array $data, $image = null): Product
    {
        return DB::transaction(function () use ($data, $image) {
            // Generar SKU si no viene
            if (empty($data['sku'])) {
                $data['sku'] = $this->generateSku();
            }

            // Generar Slug
            $data['slug'] = Str::slug($data['name']);

            // Gestionar Imagen usando el Trait
            if ($image) {
                $data['image_path'] = $this->handleUpload($image, 'products');
            }

            return Product::create($data);
        });
    }

    public function updateProduct(Product $product, array $data, $image = null): bool
    {
        return DB::transaction(function () use ($product, $data, $image) {
            // Si hay imagen nueva, el Trait borra la vieja automáticamente
            if ($image) {
                $data['image_path'] = $this->handleUpload($image, 'products', $product->image_path);
            }

            return $product->update($data);
        });
    }

    /**
     * Generador de SKU correlativo
     */
    private function generateSku(): string
    {
        $lastId = Product::withTrashed()->max('id') ?? 0;
        return 'PRD-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Ejecuta acciones sobre múltiples productos a la vez
     */
    public function performBulkAction(array $ids, string $action, $value = null): int
    {
        return DB::transaction(function () use ($ids, $action, $value) {
            $query = Product::whereIn('id', $ids);
            $count = count($ids);

            match ($action) {
                'change_active'     => $query->update(['is_active' => $value]),
                'change_stockable'  => $query->update(['is_stockable' => $value]),
                'change_category'   => $query->update(['category_id' => $value]),
                'change_unit'       => $query->update(['unit_id' => $value]),
                default => throw new \InvalidArgumentException("Acción no soportada"),
            };

            return $count;
        });
    }

    public function getActionLabel(string $action): string
    {
        return match ($action) {
            'change_active'     => 'actualizado el estado operativo',
            'change_stockable'  => 'actualizado la gestión de stock',
            'change_category'   => 'cambiado de categoría',
            'change_unit'       => 'cambiado de unidad',
            default             => 'procesado',
        };
    }
}