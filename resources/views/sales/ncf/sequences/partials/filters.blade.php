<x-data-table.filter-container formId="ncf-sequences-filters">
    <div class="w-full lg:flex-1">
        {{-- Espacio vacío o podrías poner una leyenda de ayuda --}}
        <span class="text-sm text-gray-500 italic">Filtrar lotes por tipo o vigencia</span>
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center justify-end gap-2">
        <x-data-table.per-page-selector formId="ncf-sequences-filters" />

        <x-data-table.filter-dropdown>
            {{-- Filtro de Tipo de Comprobante --}}
            <x-data-table.filter-select label="Tipo de NCF" name="ncf_type_id" formId="ncf-sequences-filters">
                <option value="">Todos los tipos</option>
                @foreach($ncf_types as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Filtro de Estado --}}
            <x-data-table.filter-select label="Estado" name="status" formId="ncf-sequences-filters">
                <option value="">Todos los estados</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </x-data-table.filter-select>
        </x-data-table.filter-dropdown>

        <x-data-table.column-selector 
            :allColumns="$allColumns" 
            :visibleColumns="$visibleColumns" 
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="ncf-sequences-filters" 
        />
    </div>
</x-data-table.filter-container>