<x-data-table.filter-container formId="receivables-filters">
    <div class="w-full lg:flex-1">
        <x-data-table.search 
            formId="receivables-filters" 
            placeholder="Buscar por No. Factura o concepto..." 
        />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center gap-2">
        <div class="flex items-center gap-2">
            <x-data-table.per-page-selector formId="receivables-filters" />
            
            <x-data-table.filter-dropdown>
                <x-data-table.filter-select label="Cliente" name="client_id" formId="receivables-filters">
                    <option value="">Todos los clientes</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </x-data-table.filter-select>

                <x-data-table.filter-select label="Estado de Factura" name="status" formId="receivables-filters">
                    <option value="">Todos los estados</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </x-data-table.filter-select>

                

                {{-- Filtro Overdue (Radio/Toggle) --}}
                <x-data-table.filter-toggle 
                    label="Antigüedad" 
                    name="overdue"
                    :options="['' => 'Todas', 'yes' => 'Vencidas', 'no' => 'Al día']"
                    formId="receivables-filters" 
                />

                

                {{-- Filtro de Rango de Saldo (EL NUEVO) --}}
                <x-data-table.filter-range 
                    label="Rango de Saldo Pendiente" 
                    nameMin="min_balance" 
                    nameMax="max_balance" 
                    formId="receivables-filters" 
                />
            </x-data-table.filter-dropdown>
        </div>

        <x-data-table.column-selector 
            :allColumns="$allColumns"
            :visibleColumns="$visibleColumns"
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="receivables-filters"
        />
    </div>
</x-data-table.filter-container>