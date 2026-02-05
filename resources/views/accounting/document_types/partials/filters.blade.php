<x-data-table.filter-container formId="documentTypes-filters">
    <div class="w-full lg:flex-1">
        <x-data-table.search 
            formId="documentTypes-filters" 
            placeholder="Buscar por nombre, código o prefijo..." 
        />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center gap-2">
        <div class="flex items-center gap-2">
            <x-data-table.per-page-selector formId="documentTypes-filters" />
            
            <x-data-table.filter-dropdown>
                <x-data-table.filter-toggle 
                    label="Estado" 
                    name="active"
                    :options="['' => 'Todos', '1' => 'Activos', '0' => 'Inactivos']"
                    formId="documentTypes-filters" 
                />
            </x-data-table.filter-dropdown>
        </div>

        <x-data-table.column-selector 
            :allColumns="$allColumns"
            :visibleColumns="$visibleColumns"
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="documentTypes-filters"
        />
    </div>
</x-data-table.filter-container>