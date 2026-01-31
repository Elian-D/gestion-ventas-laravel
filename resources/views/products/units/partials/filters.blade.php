<x-data-table.filter-container formId="units-filters">

    <div class="w-full lg:flex-1">
        <x-data-table.search 
            formId="units-filters" 
            placeholder="Buscar por id, nombre..." 
        />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center gap-2">

        <div class="flex items-center gap-2">
            <x-data-table.per-page-selector formId="units-filters" />
            
            <x-data-table.filter-dropdown>

            <x-data-table.filter-toggle 
                    label="Estado" 
                    name="is_active"
                    :options="['' => 'Todos', '1' => 'Activos', '0' => 'Inactivos']"
                    formId="units-filters" 
                />

            </x-data-table.filter-dropdown>
        </div>
        <x-data-table.column-selector 
            :allColumns="$allColumns"
            :visibleColumns="$visibleColumns"
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="units-filters"
        />
    </div>

</x-data-table.filter-container>
