<?php

namespace App\Tables;

class ProductTable
{
    public static function allColumns(): array
    {
        return [
            'sku'               => 'Código',
            'name'              => 'Nombre',
            'image_path'        => 'Imagen',
            'category_id'       => 'Categoría',
            'description'       => 'Descripción',
            'price'             => 'Precio',
            'cost'              => 'Costo',
            'unit_id'           => 'Unidad de Medida',
            'stock'             => 'Stock',
            'min_stock'         => 'Stock Mínimo',
            'is_active'         => 'Estado',
            'is_stockable'      => 'Gestionar Stock',
            'created_at'        => 'Fecha Creación',
            'updated_at'        => 'Última Actualización',
        ];
    }

    public static function defaultDesktop(): array
    {
        return [
            'sku',
            'name',
            'image_path',
            'category_id',
            'price',
            'unit_id',
            'stock',
            'is_active',
            'description',
        ];
    }

    public static function defaultMobile(): array
    {
        return [
            'sku',
            'name',
        ];
    }
}
