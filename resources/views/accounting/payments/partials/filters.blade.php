<x-data-table.filter-container formId="payments-filters">
    <div class="w-full lg:flex-1">
        <x-data-table.search 
            formId="payments-filters" 
            placeholder="Buscar por No. Recibo, referencia o nota..." 
        />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center justify-end gap-2">
        <x-data-table.per-page-selector formId="payments-filters" />

        <x-data-table.filter-dropdown>
            {{-- Filtro de Cliente --}}
            <x-data-table.filter-select label="Cliente" name="client_id" formId="payments-filters">
                <option value="">Todos los clientes</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Filtro de Método de Pago --}}
            <x-data-table.filter-select label="Método de Pago" name="tipo_pago_id" formId="payments-filters">
                <option value="">Todos los métodos</option>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method->id }}">{{ $method->nombre }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Filtro de Estado --}}
            <x-data-table.filter-select label="Estado" name="status" formId="payments-filters">
                <option value="">Todos los estados</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Rango de Fecha de Pago --}}
            <x-data-table.filter-datetime-range 
                label="Fecha de Pago" 
                formId="payments-filters" 
                nameFrom="from_date"
                nameTo="to_date"
            />

            {{-- Rango de Montos --}}
            <x-data-table.filter-range 
                label="Monto Pagado" 
                nameMin="min_amount" 
                nameMax="max_amount" 
                formId="payments-filters" 
            />
        </x-data-table.filter-dropdown>

        <x-data-table.column-selector 
            :allColumns="$allColumns" 
            :visibleColumns="$visibleColumns" 
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="payments-filters" 
        />
    </div>
</x-data-table.filter-container>