<x-data-table.filter-container formId="products-filters">
    
    {{-- Búsqueda Global --}}
    <div class="w-full lg:flex-1">
        <x-data-table.search formId="products-filters" placeholder="Buscar por nombre, SKU o descripción..." />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center justify-between sm:justify-start lg:justify-end gap-2">
        
        {{-- Acciones Masivas --}}
        <x-data-table.bulk-actions :actions="[
            [
                'id' => 'change_active',
                'type' => 'select', 
                'label' => 'Estado Operativo', 
                'icon' => 'heroicon-s-check-circle',
                'options' => [['id' => '1', 'label' => 'Activar'], ['id' => '0', 'label' => 'Desactivar']]
            ],
            [
                'id' => 'change_stockable',
                'type' => 'select', 
                'label' => 'Gestión Stock', 
                'icon' => 'heroicon-s-cube',
                'options' => [['id' => '1', 'label' => 'Habilitar'], ['id' => '0', 'label' => 'Deshabilitar']]
            ],
            [
                'id' => 'change_category',
                'type' => 'select', 
                'label' => 'Cambiar Categoría', 
                'icon' => 'heroicon-s-tag',
                'options' => $categories->map(fn($c) => ['id' => $c->id, 'label' => $c->name])
            ],
            [
                'id' => 'change_unit',
                'type' => 'select', 
                'label' => 'Cambiar Unidad', 
                'icon' => 'heroicon-s-scale',
                'options' => $units->map(fn($u) => ['id' => $u->id, 'label' => $u->name])
            ],
        ]" />

        <div class="flex items-center gap-2">
            {{-- Selector de cantidad por página --}}
            <x-data-table.per-page-selector formId="products-filters" />

            {{-- Dropdown de Filtros Avanzados --}}
            <x-data-table.filter-dropdown>
                
                {{-- Filtro Categoría --}}
                <x-data-table.filter-select label="Categoría" name="categories" formId="products-filters">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-data-table.filter-select>

                {{-- Filtro Unidad --}}
                <x-data-table.filter-select label="Unidad de Medida" name="units" formId="products-filters">
                    <option value="">Todas las unidades</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->abbreviation }})</option>
                    @endforeach
                </x-data-table.filter-select>

                {{-- Filtro Estado --}}
                <x-data-table.filter-toggle label="Estado del Producto" name="is_active" 
                    :options="['' => 'Todos', '1' => 'Activos', '0' => 'Inactivos']" formId="products-filters" />

            </x-data-table.filter-dropdown>
        </div>

        {{-- Selector de Columnas --}}
        <x-data-table.column-selector 
            :allColumns="$allColumns" 
            :visibleColumns="$visibleColumns" 
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="products-filters" 
        />
    </div>
</x-data-table.filter-container>