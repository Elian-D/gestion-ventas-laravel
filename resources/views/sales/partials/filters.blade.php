<x-data-table.filter-container formId="sales-filters">
    <div class="w-full lg:flex-1">
        <x-data-table.search 
            formId="sales-filters" 
            placeholder="Buscar por No. factura, notas o referencia..." 
        />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center justify-end gap-2">
        <x-data-table.per-page-selector formId="sales-filters" />

        <x-data-table.filter-dropdown>
            {{-- Filtro de Cliente --}}
            <x-data-table.filter-select label="Cliente" name="client_id" formId="sales-filters">
                <option value="">Todos los clientes</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Filtro de Almacén --}}
            <x-data-table.filter-select label="Almacén" name="warehouse_id" formId="sales-filters">
                <option value="">Todos los almacenes</option>
                @foreach($warehouses as $wh)
                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Filtro de Tipo de Pago --}}
            <x-data-table.filter-select label="Tipo de Pago" name="payment_type" formId="sales-filters">
                <option value="">Todos los tipos</option>
                @foreach($payment_types as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- NUEVO: Filtro de Método de Pago (Detallado) --}}
            <x-data-table.filter-select label="Método (Efectivo/Transf.)" name="tipo_pago_id" formId="sales-filters">
                <option value="">Todos los métodos</option>
                @foreach($tipo_pagos as $pago)
                    <option value="{{ $pago->id }}">{{ $pago->nombre }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Filtro de Estado --}}
            <x-data-table.filter-select label="Estado" name="status" formId="sales-filters">
                <option value="">Todos los estados</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </x-data-table.filter-select>

            {{-- Rango de Fecha de Venta --}}
            <x-data-table.filter-datetime-range 
                label="Fecha de Venta" 
                formId="sales-filters" 
                nameFrom="from_date"
                nameTo="to_date"
            />

            {{-- Rango de Montos Totales --}}
            <x-data-table.filter-range 
                label="Monto Total" 
                nameMin="min_amount" 
                nameMax="max_amount" 
                formId="sales-filters" 
            />
        </x-data-table.filter-dropdown>

        <x-data-table.column-selector 
            :allColumns="$allColumns" 
            :visibleColumns="$visibleColumns" 
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="sales-filters" 
        />
    </div>
</x-data-table.filter-container>