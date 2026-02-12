<x-data-table.filter-container formId="ncf-logs-filters">
    <div class="w-full lg:flex-1">
        <x-data-table.search 
            formId="ncf-logs-filters" 
            placeholder="Buscar por NCF completo (ej: B0100000001)..." 
        />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center justify-end gap-2">
        <x-data-table.per-page-selector formId="ncf-logs-filters" />

        <x-data-table.filter-dropdown>
        {{-- Filtro de Tipo de NCF --}}
        <x-data-table.filter-select label="Tipo de Comprobante" name="ncf_type_id" formId="ncf-logs-filters">
            <option value="">Todos los tipos</option>
            {{-- CAMBIO AQUÍ: Iteramos llave => valor porque ncf_types es un array de pluck --}}
            @foreach($ncf_types as $id => $displayName)
                <option value="{{ $id }}">{{ $displayName }}</option>
            @endforeach
        </x-data-table.filter-select>

            {{-- Filtro de Estado (Usado/Anulado) --}}
            <x-data-table.filter-select label="Estado" name="status" formId="ncf-logs-filters">
                <option value="">Todos los estados</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Rango de Fecha de Uso --}}
            <x-data-table.filter-datetime-range 
                label="Fecha de Emisión" 
                formId="ncf-logs-filters" 
                nameFrom="from_date"
                nameTo="to_date"
            />
        </x-data-table.filter-dropdown>

        <x-data-table.column-selector 
            :allColumns="$allColumns" 
            :visibleColumns="$visibleColumns" 
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="ncf-logs-filters" 
        />
    </div>
</x-data-table.filter-container>