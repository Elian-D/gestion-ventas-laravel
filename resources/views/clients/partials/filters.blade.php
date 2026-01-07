<div x-data="{ open: false }" class="mb-4">
    <form id="clients-filters" method="GET">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-3">
            
            <x-data-table.per-page-selector />

            <x-data-table.filter-dropdown>
    
                {{-- Filtro de Estado Operativo --}}
                <x-data-table.filter-select label="Estado Operativo" name="active">
                    <option value="">Todos</option>
                    <option value="1" @selected(request('active') === '1')>Activos</option>
                    <option value="0" @selected(request('active') === '0')>Inactivos</option>
                </x-data-table.filter-select>

                {{-- Filtro de Estado del Cliente (Dinámico) --}}
                <x-data-table.filter-select label="Estado del Cliente" name="estado_cliente">
                    <option value="">Todos los estados</option>
                    @foreach($estadosClientes as $estado)
                        <option value="{{ $estado->id }}" @selected(request('estado_cliente') == $estado->id)>
                            {{ $estado->nombre }}
                        </option>
                    @endforeach
                </x-data-table.filter-select>

                {{-- Filtro de Tipo de Negocio (Dinámico) --}}
                <x-data-table.filter-select label="Tipo de Negocio" name="business_type">
                    <option value="">Todos los tipos</option>
                    @foreach($tiposNegocio as $tipo)
                        <option value="{{ $tipo->id }}" @selected(request('business_type') == $tipo->id)>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </x-data-table.filter-select>

            </x-data-table.filter-dropdown>
            
            <x-data-table.column-selector 
                :allColumns="$allColumns" 
                :visibleColumns="$visibleColumns" 
                />

            <x-data-table.search placeholder="Buscar cliente..." />
        </div>
    </form>

    {{-- CHIPS ACTIVOS (FUNCIONALES) --}}
    <div id="active-filters" class="flex flex-wrap items-center gap-2 mt-4"></div>

</div>