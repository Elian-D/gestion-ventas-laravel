<x-data-table.filter-container formId="invoices-filters">
    {{-- Búsqueda por No. de Factura o Referencias --}}
    <div class="w-full lg:flex-1">
        <x-data-table.search 
            formId="invoices-filters" 
            placeholder="Buscar por No. de factura o referencia de venta..." 
        />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center justify-end gap-2">
        <x-data-table.per-page-selector formId="invoices-filters" />

        <x-data-table.filter-dropdown>
            {{-- Filtro de Cliente --}}
            <x-data-table.filter-select label="Cliente" name="client_id" formId="invoices-filters">
                <option value="">Todos los clientes</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Filtro de Tipo de Venta (Contado/Crédito) --}}
            <x-data-table.filter-select label="Tipo de Pago" name="type" formId="invoices-filters">
                <option value="">Todos los tipos</option>
                @foreach($payment_types as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Filtro de Estado Legal (Vigente/Anulada) --}}
            <x-data-table.filter-select label="Estado Documento" name="status" formId="invoices-filters">
                <option value="">Todos los estados</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Filtro de Formato de Impresión --}}
            <x-data-table.filter-select label="Formato Original" name="format_type" formId="invoices-filters">
                <option value="">Todos los formatos</option>
                @foreach($formats as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Rango de Fecha de Emisión --}}
            <x-data-table.filter-datetime-range 
                label="Fecha de Emisión" 
                formId="invoices-filters" 
                nameFrom="from_date"
                nameTo="to_date"
            />
        </x-data-table.filter-dropdown>

        {{-- Selector de Columnas Visibles --}}
        <x-data-table.column-selector 
            :allColumns="$allColumns" 
            :visibleColumns="$visibleColumns" 
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="invoices-filters" 
        />
    </div>
</x-data-table.filter-container>