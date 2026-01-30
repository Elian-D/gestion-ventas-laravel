<x-data-table.filter-container formId="categories-filters">

    <div class="w-full lg:flex-1">
        <x-data-table.search 
            formId="categories-filters" 
            placeholder="Buscar por id, nombre..." 
        />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center gap-2">

        <div class="flex items-center gap-2">
            <x-data-table.per-page-selector formId="categories-filters" />
            
            <x-data-table.filter-dropdown>

            <x-data-table.filter-toggle 
                    label="Estado" 
                    name="is_active"
                    :options="['' => 'Todos', '1' => 'Activos', '0' => 'Inactivos']"
                    formId="categories-filters" 
                />

            </x-data-table.filter-dropdown>
        </div>
        <x-data-table.column-selector 
            :allColumns="$allColumns"
            :visibleColumns="$visibleColumns"
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="categories-filters"
        />
    </div>

</x-data-table.filter-container>
