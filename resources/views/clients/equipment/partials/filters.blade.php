<x-data-table.filter-container formId="equipments-filters">

    <div class="w-full lg:flex-1">
        <x-data-table.search 
            formId="equipments-filters" 
            placeholder="Buscar por código, nombre o serie..." 
        />
    </div>

    <div class="w-full lg:w-auto flex flex-wrap items-center gap-2">

        <x-data-table.bulk-actions :actions="[
            [
                'id' => 'change_active',
                'type' => 'select',
                'label' => 'Estado',
                'icon' => 'heroicon-s-check-circle',
                'options' => [
                    ['id' => '1', 'label' => 'Activar'],
                    ['id' => '0', 'label' => 'Desactivar'],
                ]
            ],
            [
                'id' => 'change_type',
                'type' => 'select',
                'label' => 'Tipo de Equipo',
                'icon' => 'heroicon-s-cog',
                'options' => $equipmentTypes->map(fn($t) => [
                    'id' => $t->id,
                    'label' => $t->nombre
                ])
            ],
            [
                'id' => 'change_pos',
                'type' => 'select',
                'label' => 'Asignar POS',
                'icon' => 'heroicon-s-building-storefront',
                'options' => $pointsOfSale->map(fn($p) => [
                    'id' => $p->id,
                    'label' => $p->name
                ])
            ],
        ]" />

        <div class="flex items-center gap-2">
            <x-data-table.per-page-selector formId="equipments-filters" />
            
            <x-data-table.filter-dropdown>


                <x-data-table.filter-select 
                    label="Tipo de Equipo" 
                    name="equipment_type_id" 
                    formId="equipments-filters">
                    <option value="">Todos</option>
                    @foreach($equipmentTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->nombre }}</option>
                    @endforeach
                </x-data-table.filter-select>

                <x-data-table.filter-select 
                    label="Punto de Venta" 
                    name="point_of_sale_id" 
                    formId="equipments-filters">
                    <option value="">Todos</option>
                    @foreach($pointsOfSale as $pos)
                        <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                    @endforeach
                </x-data-table.filter-select>

                <x-data-table.filter-toggle 
                    label="Estado" 
                    name="active"
                    :options="['' => 'Todos', '1' => 'Activos', '0' => 'Inactivos']"
                    formId="equipments-filters" 
                />

            </x-data-table.filter-dropdown>
        </div>
        <x-data-table.column-selector 
            :allColumns="$allColumns"
            :visibleColumns="$visibleColumns"
            :defaultDesktop="$defaultDesktop"
            :defaultMobile="$defaultMobile"
            formId="equipments-filters"
        />
    </div>

</x-data-table.filter-container>
