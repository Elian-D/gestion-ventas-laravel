<x-data-table.filter-container formId="stocks-filters">

    {{-- Búsqueda Global (Producto o SKU) --}}
    <div class="w-full lg:flex-1">
        <x-data-table.search 
            formId="stocks-filters" 
            placeholder="Buscar por producto o SKU..." 
        />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center gap-2">
        <div class="flex items-center gap-2">
            <x-data-table.per-page-selector formId="stocks-filters" />
            
            <x-data-table.filter-dropdown>
                {{-- Filtro por Almacén --}}
                <x-data-table.filter-select label="Ubicación/Almacén" name="warehouse_id" formId="stocks-filters">
                    <option value="">Todas las ubicaciones</option>
                    @foreach($warehouses as $w)
                        <option value="{{ $w->id }}" {{ request('warehouse_id') == $w->id ? 'selected' : '' }}>
                            {{ $w->name }}
                        </option>
                    @endforeach
                </x-data-table.filter-select>

                {{-- Filtro por Categoría --}}
                <x-data-table.filter-select label="Categoría" name="category_id" formId="stocks-filters">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </x-data-table.filter-select>

                {{-- Filtro por Salud de Stock --}}
                <x-data-table.filter-select label="Estado de Stock" name="status" formId="stocks-filters">
                    <option value="">Cualquier estado</option>
                    <option value="ok" class="text-green-600">Stock Suficiente</option>
                    <option value="low" class="text-amber-600">Stock Bajo</option>
                    <option value="out" class="text-red-600">Agotado</option>
                </x-data-table.filter-select>

            </x-data-table.filter-dropdown>
        </div>

        <x-data-table.column-selector 
            :allColumns="$allColumns"
            :visibleColumns="$visibleColumns"
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="stocks-filters"
        />
    </div>

</x-data-table.filter-container>