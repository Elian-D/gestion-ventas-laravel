<?php

namespace App\Services\Products;

use App\Models\Products\Category;
use App\Models\Products\Unit;

class ProductCatalogService
{
    /**
     * Datos para los filtros de la tabla
     */
    public function getForFilters(): array
    {
        return [
            'categories' => Category::activos()
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),

            'units' => Unit::activos()
                ->select('id', 'name', 'abbreviation')
                ->orderBy('name')
                ->get(),
        ];
    }

    /**
     * Datos para el formulario de Create/Edit
     */
    public function getForForm(): array
    {
        // En este caso, como los filtros y el formulario usan lo mismo, 
        // podrías incluso llamar a getForFilters() para no repetir código.
        return $this->getForFilters();
    }
}