<x-data-table.filter-container formId="warehouses-filters">

    {{-- Búsqueda Global --}}
    <div class="w-full lg:flex-1">
        <x-data-table.search 
            formId="warehouses-filters" 
            placeholder="Buscar por código, nombre..." 
        />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center gap-2">

        <div class="flex items-center gap-2">
            {{-- Selector de cantidad por página --}}
            <x-data-table.per-page-selector formId="warehouses-filters" />
            
            {{-- Dropdown de Filtros Avanzados --}}
            <x-data-table.filter-dropdown>
                
                {{-- Filtro de Tipo de Almacén (Ahora como Select) --}}
                <x-data-table.filter-select label="Tipo de Almacén" name="type" formId="warehouses-filters">
                    <option value="">Todos los tipos</option>
                    @foreach($types as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </x-data-table.filter-select>

                {{-- Filtro de Estado (Mantenemos el Toggle por ser Booleano) --}}
                <x-data-table.filter-toggle 
                    label="Estado" 
                    name="is_active"
                    :options="['' => 'Todos', '1' => 'Activos', '0' => 'Inactivos']"
                    formId="warehouses-filters" 
                />

            </x-data-table.filter-dropdown>
        </div>

        {{-- Selector de Columnas --}}
        <x-data-table.column-selector 
            :allColumns="$allColumns"
            :visibleColumns="$visibleColumns"
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="warehouses-filters"
        />
    </div>

</x-data-table.filter-container>