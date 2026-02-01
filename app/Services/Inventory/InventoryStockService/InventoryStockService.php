<?php

namespace App\Services\Inventory\InventoryStockService;

use App\Models\Inventory\InventoryStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryStockService
{
    /**
     * Obtiene los balances filtrados y paginados
     */
    public function getBalances($filters, int $perPage = 15): LengthAwarePaginator
    {
        // Usamos el scope de búsqueda y filtros definido en el Pipeline
        $query = InventoryStock::with(['product.category', 'warehouse', 'product.unit']);

        // Aquí se aplicará el pipeline de filtros que construiremos luego
        return $query->paginate($perPage);
    }

    /**
     * Actualiza el stock mínimo de una ubicación específica
     */
    public function updateMinStock(InventoryStock $stock, float $value): bool
    {
        return $stock->update([
            'min_stock' => $value
        ]);
    }
}