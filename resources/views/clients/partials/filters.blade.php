<x-data-table.filter-container formId="clients-filters">
    
    {{-- BUSCADOR: Ocupa todo el ancho en móvil y crece en escritorio --}}
    <div class="w-full md:flex-grow order-1">
        <x-data-table.search 
            formId="clients-filters" 
            placeholder="Buscar cliente..." 
        />
    </div>

    {{-- ACCIONES: Se distribuyen equitativamente en móvil --}}
    <div class="w-full md:w-auto flex items-center justify-between md:justify-end gap-2 order-2">
        
        {{-- Grupo Izquierdo (en móvil) --}}
        <div class="flex items-center gap-2">
            <x-data-table.bulk-actions :actions="[
            [
                'id' => 'change_status',
                'type' => 'select', 
                'label' => 'Cambiar Estado', 
                'icon' => 'heroicon-s-user-group',
                'options' => $estadosClientes->map(fn($e) => ['id' => $e->id, 'label' => $e->nombre])
            ],

            [
            'id' => 'change_geo_state',
            'type' => 'select', 
            'label' => 'Cambiar Región', 
            'icon' => 'heroicon-s-map-pin',
            'options' => $states->map(fn($s) => ['id' => $s->id, 'label' => $s->name])
            ],

            ['id' => 'activate', 'type' => 'none', 'label' => 'Activar', 'icon' => 'heroicon-s-check-circle'],
            ['id' => 'deactivate', 'type' => 'none', 'label' => 'Desactivar', 'icon' => 'heroicon-s-x-circle'],
            ['id' => 'delete', 'type' => 'none', 'label' => 'Eliminar', 'icon' => 'heroicon-s-trash'],
            ]" />
            <x-data-table.per-page-selector formId="clients-filters" />
            
            <x-data-table.filter-dropdown>
                <x-data-table.filter-select label="Estado Operativo" name="active" formId="clients-filters">
                    <option value="">Todos</option>
                    <option value="1" @selected(request('active') === '1')>Activos</option>
                    <option value="0" @selected(request('active') === '0')>Inactivos</option>
                </x-data-table.filter-select>

                <x-data-table.filter-select label="Estado del Cliente" name="estado_cliente" formId="clients-filters">
                    <option value="">Todos los estados</option>
                    @foreach($estadosClientes as $estado)
                        <option value="{{ $estado->id }}" @selected(request('estado_cliente') == $estado->id)>
                            {{ $estado->nombre }}
                        </option>
                    @endforeach
                </x-data-table.filter-select>

                <x-data-table.filter-select label="Tipo de Negocio" name="business_type" formId="clients-filters">
                    <option value="">Todos los tipos</option>
                    @foreach($tiposNegocio as $tipo)
                        <option value="{{ $tipo->id }}" @selected(request('business_type') == $tipo->id)>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </x-data-table.filter-select>
            </x-data-table.filter-dropdown>
        </div>

        {{-- Grupo Derecho (en móvil) --}}
        <x-data-table.column-selector 
            :allColumns="$allColumns" 
            :visibleColumns="$visibleColumns" 
            :defaultVisible="$defaultVisible"
            formId="clients-filters" 
        />
    </div>

</x-data-table.filter-container>