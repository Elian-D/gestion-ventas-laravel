{{-- MODAL CREAR --}}
    <x-modal name="crear-warehouse" maxWidth="md">

        <x-form-header
            title="Nuevo Almacén"
            subtitle="Registre una nueva ubicación de inventario (fija o móvil)."
            :back-route="route('inventory.warehouses.index')" />

        <form action="{{ route('inventory.warehouses.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-4">
                {{-- Nombre --}}
                <div>
                    <x-input-label for="name" value="Nombre del almacén" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="Ej: Bodega Central o Camión #01" required />
                </div>

                {{-- Tipo de Almacén --}}
                <div>
                    <x-input-label for="type" value="Tipo de ubicación" />
                    <select name="type" id="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Dirección/Ubicación --}}
                <div>
                    <x-input-label for="address" value="Dirección o Referencia" />
                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" placeholder="Dirección física o placa del vehículo" />
                </div>

                {{-- Descripción --}}
                <div>
                    <x-input-label for="description" value="Descripción (Opcional)" />
                    <textarea name="description" id="description" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">{{ old('description') }}</textarea>
                </div>

                {{-- Estado Operativo (Mantenemos tu diseño de radios) --}}
                <div>
                    <x-input-label value="Estado Operativo" />
                    <div class="flex p-1 bg-gray-100 rounded-lg mt-1 w-full">
                        <label class="flex-1">
                            <input type="radio" name="is_active" value="1" class="peer hidden" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            <span class="block text-center px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all text-gray-500 hover:text-gray-700 peer-checked:bg-green-500 peer-checked:text-white peer-checked:shadow-sm">
                                Activo
                            </span>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="is_active" value="0" class="peer hidden" {{ old('is_active') == '0' ? 'checked' : '' }}>
                            <span class="block text-center px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all text-gray-500 hover:text-gray-700 peer-checked:bg-red-500 peer-checked:text-white peer-checked:shadow-sm">
                                Inactivo
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="bg-green-600">Guardar Almacén</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- MODAL EDITAR --}}
    @foreach($warehouses as $item)
    <x-modal name="edit-warehouse-{{ $item->id }}" maxWidth="md">

        <x-form-header
            title="Editar Almacén: {{ $item->name }}"
            subtitle="Modifique la información de la ubicación."
            :back-route="route('inventory.warehouses.index')" />

        <form method="POST" action="{{ route('inventory.warehouses.update', $item) }}" class="p-6">
            @csrf @method('PUT')

            <div class="space-y-4">
                <div>
                    <x-input-label value="Nombre del almacén" />
                    <x-text-input name="name" type="text" class="mt-1 block w-full" value="{{ $item->name }}" required />
                </div>

                <div>
                    <x-input-label value="Tipo de ubicación" />
                    <select name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ (old('type', $item->type) == $value) ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label value="Dirección o Referencia" />
                    <x-text-input name="address" type="text" class="mt-1 block w-full" value="{{ $item->address }}" />
                </div>

                <div>
                    <x-input-label value="Descripción" />
                    <textarea name="description" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">{{ old('description', $item->description) }}</textarea>
                </div>

                <div>
                    <x-input-label value="Estado Operativo" />
                    <div class="flex p-1 bg-gray-100 rounded-lg mt-1 w-full">
                        <label class="flex-1">
                            <input type="radio" name="is_active" value="1" class="peer hidden" {{ old('is_active', $item->is_active) == '1' ? 'checked' : '' }}>
                            <span class="block text-center px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all text-gray-500 hover:text-gray-700 peer-checked:bg-green-500 peer-checked:text-white peer-checked:shadow-sm">
                                Activo
                            </span>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="is_active" value="0" class="peer hidden" {{ old('is_active', $item->is_active) == '0' ? 'checked' : '' }}>
                            <span class="block text-center px-3 py-2 text-sm font-medium rounded-md cursor-pointer transition-all text-gray-500 hover:text-gray-700 peer-checked:bg-red-500 peer-checked:text-white peer-checked:shadow-sm">
                                Inactivo
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="bg-green-600">Actualizar Almacén</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-ui.confirm-deletion-modal 
        :id="$item->id"
        :title="'¿Eliminar Almacén?'"
        :itemName="$item->name"
        :type="'el almacén'"
        :route="route('inventory.warehouses.destroy', $item)"
    />
    @endforeach