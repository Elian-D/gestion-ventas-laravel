<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Warehouse;
use App\Models\Inventory\InventoryMovement;
use App\Models\Products\Product; // Asegúrate de que la ruta sea correcta

class MovementCatalogService
{
    public function getForFilters(): array
    {
        return [
            'warehouses' => Warehouse::activos()
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),
            
            'types' => InventoryMovement::getTypes(),
            
            // Opcional: Solo productos que son "stockeables"
            'products' => Product::where('is_stockable', true)
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),
        ];
    }

    public function getForForm(): array
    {
        return $this->getForFilters();
    }
}