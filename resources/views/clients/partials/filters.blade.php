<x-data-table.filter-container formId="clients-filters">
    
    {{-- BUSCADOR: Ocupa todo el ancho en móvil y crece en escritorio --}}
    <div class="w-full lg:flex-1">
        <x-data-table.search 
            formId="clients-filters" 
            placeholder="Buscar cliente..." 
        />
    </div>

    {{-- ACCIONES: Se distribuyen equitativamente en móvil --}}
    <div class="w-full lg:w-auto flex flex-wrap items-center justify-between sm:justify-start lg:justify-end gap-2">
        
        {{-- Grupo Izquierdo (en móvil) --}}
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

        ['id' => 'delete', 'type' => 'none', 'label' => 'Eliminar', 'icon' => 'heroicon-s-trash'],
        ]" />

        <div class="flex items-center gap-2">
            <x-data-table.per-page-selector formId="clients-filters" />

            <x-data-table.filter-dropdown>

                {{-- Filtro de Tipo de Cliente --}}
                <x-data-table.filter-toggle label="Tipo de Cliente" name="type" 
                    :options="['' => 'Todos', 'individual' => 'Individuales', 'company' => 'Empresas']" formId="clients-filters" />


                {{-- Filtro de Tipo de Identificador --}}
                <x-data-table.filter-select label="Tipo Identificador" name="tax_type" formId="clients-filters">
                    <option value="">Todos los documentos</option>
                    @foreach($taxIdentifierTypes as $taxType)
                        <option value="{{ $taxType->id }}" @selected(request('tax_type') == $taxType->id)>
                            {{ $taxType->name }}
                        </option>
                    @endforeach
                </x-data-table.filter-select>

                <x-data-table.filter-select label="Estado del Cliente" name="estado_cliente" formId="clients-filters">
                    <option value="">Todos los estados</option>
                    @foreach($estadosClientes as $estado)
                        <option value="{{ $estado->id }}" @selected(request('estado_cliente') == $estado->id)>
                            {{ $estado->nombre }}
                        </option>
                    @endforeach
                </x-data-table.filter-select>

                {{-- Filtro de Ubicación --}}
                <x-data-table.filter-select label="Provincia/Estado" name="state" formId="clients-filters">
                    <option value="">Todas las ubicaciones</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}" @selected(request('state') == $state->id)>
                            {{ $state->name }}
                        </option>
                    @endforeach
                </x-data-table.filter-select>

                <x-data-table.filter-date-range 
                    label="Fecha de Registro" 
                    formId="clients-filters" 
                />

            </x-data-table.filter-dropdown>
        </div>

        {{-- Grupo Derecho (en móvil) --}}
        <x-data-table.column-selector 
            :allColumns="$allColumns" 
            :visibleColumns="$visibleColumns" 
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="clients-filters" 
        />
    </div>

</x-data-table.filter-container>