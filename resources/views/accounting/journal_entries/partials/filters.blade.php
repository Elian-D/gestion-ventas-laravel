<x-data-table.filter-container formId="journals-filters">
    <div class="w-full lg:flex-1">
        <x-data-table.search formId="journals-filters" placeholder="Buscar por referencia o concepto..." />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center justify-end gap-2">
        <x-data-table.per-page-selector formId="journals-filters" />

        <x-data-table.filter-dropdown>
            {{-- Filtro de Estado --}}
            <x-data-table.filter-select label="Estado" name="status" formId="journals-filters">
                <option value="">Todos los estados</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Rango de Fecha --}}
            <x-data-table.filter-datetime-range label="Rango de Fecha" formId="journals-filters" />
            
        </x-data-table.filter-dropdown>

        <x-data-table.column-selector 
            :allColumns="$allColumns" 
            :visibleColumns="$visibleColumns" 
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="journals-filters" 
        />
    </div>
</x-data-table.filter-container>