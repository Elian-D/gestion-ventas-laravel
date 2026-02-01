<x-data-table.filter-container formId="movements-filters">
    <div class="w-full lg:flex-1">
        <x-data-table.search formId="movements-filters" placeholder="Buscar por producto o descripción..." />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center justify-end gap-2">
        <x-data-table.per-page-selector formId="movements-filters" />

        <x-data-table.filter-dropdown>
            {{-- Filtro de Almacén --}}
            <x-data-table.filter-select label="Almacén" name="warehouse_id" formId="movements-filters">
                <option value="">Todos los almacenes</option>
                @foreach($warehouses as $wh)
                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Filtro de Tipo de Movimiento --}}
            <x-data-table.filter-select label="Tipo de Operación" name="type" formId="movements-filters">
                <option value="">Todas las operaciones</option>
                @foreach($types as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Nuevo componente de Fecha y Hora --}}
            <x-data-table.filter-datetime-range label="Rango de Fecha y Hora" formId="movements-filters" />
            
        </x-data-table.filter-dropdown>

        <x-data-table.column-selector 
            :allColumns="$allColumns" 
            :visibleColumns="$visibleColumns" 
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="movements-filters" 
        />
    </div>
</x-data-table.filter-container>