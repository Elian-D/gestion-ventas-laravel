<x-data-table.filter-container formId="pos-filters">
    
    <div class="w-full lg:flex-1">
        <x-data-table.search formId="pos-filters" placeholder="Buscar por nombre, código o ciudad..." />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center justify-between sm:justify-start lg:justify-end gap-2">
        
        <x-data-table.bulk-actions :actions="[
            [
                'id' => 'change_active',
                'type' => 'select', 
                'label' => 'Estado Op.', 
                'icon' => 'heroicon-s-check-circle',
                'options' => [['id' => '1', 'label' => 'Activar'], ['id' => '0', 'label' => 'Desactivar']]
            ],
            [
                'id' => 'change_type',
                'type' => 'select', 
                'label' => 'Tipo Negocio', 
                'icon' => 'heroicon-s-briefcase',
                'options' => $businessTypes->map(fn($t) => ['id' => $t->id, 'label' => $t->nombre])
            ],
            [
                'id' => 'change_client',
                'type' => 'select', 
                'label' => 'Cliente', 
                'icon' => 'heroicon-s-user',
                'options' => $clients->map(fn($c) => ['id' => $c->id, 'label' => $c->name])
            ],
            ['id' => 'delete', 'type' => 'none', 'label' => 'Eliminar', 'icon' => 'heroicon-s-trash'],
        ]" />

        <div class="flex items-center gap-2">
            <x-data-table.per-page-selector formId="pos-filters" />

            <x-data-table.filter-dropdown>
                {{-- Filtro Cliente --}}
                <x-data-table.filter-select label="Cliente (Cuenta)" name="client" formId="pos-filters">
                    <option value="">Todos los clientes</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </x-data-table.filter-select>

                {{-- Filtro Estado Operativo --}}
                <x-data-table.filter-toggle label="Estado Operativo" name="active" 
                    :options="['' => 'Todos', '1' => 'Activos', '0' => 'Inactivos']" formId="pos-filters" />

                {{-- Filtro Tipo de Negocio --}}
                <x-data-table.filter-select label="Tipo de Negocio" name="business_type" formId="pos-filters">
                    <option value="">Todos los tipos</option>
                    @foreach($businessTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->nombre }}</option>
                    @endforeach
                </x-data-table.filter-select>

                <x-data-table.filter-select label="Provincia" name="state" formId="pos-filters">
                    <option value="">Todas las provincias</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </x-data-table.filter-select>
            </x-data-table.filter-dropdown>
        </div>

        <x-data-table.column-selector 
            :allColumns="$allColumns" 
            :visibleColumns="$visibleColumns" 
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="pos-filters" 
        />
    </div>
</x-data-table.filter-container>