    {{-- MODAL CREAR --}}
    <x-modal name="crear-tipoEquipo" maxWidth="md">

        <x-form-header
            title="Nuevo Tipo de Equipo"
            subtitle="Registre un nuevo tipo de equipo."
            :back-route="route('clients.equipos.index')" />

        <form action="{{ route('clients.equipos.store') }}" method="POST" class="p-6">
            
            @csrf

            <div class="space-y-4">
                <div>
                    <x-input-label for="nombre" value="Nombre del Tipo de Equipo" />
                    <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" required />
                </div>

                <div>
                    <x-input-label value="Estado Operativo" />
                    <select name="activo" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 text-sm">
                        <option value="1" {{ old('activo', '1') == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="bg-green-600">Guardar Tipo de Equipo</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- MODAL EDITAR --}}
    @foreach($equipmentsTypes as $item)
    <x-modal name="edit-tipoEquipo-{{ $item->id }}" maxWidth="md">

        <x-form-header
            title="Editar Tipo de Equipo: {{ $item->nombre }}"
            subtitle="Modifique la informacion del tipo de equipo."
            :back-route="route('clients.equipos.index')" />

        <form method="POST" action="{{ route('clients.equipos.update', $item) }}" class="p-6">
            @csrf @method('PUT')

            <div class="space-y-4">
                <div>
                    <x-input-label value="Nombre" />
                    <x-text-input name="nombre" type="text" class="mt-1 block w-full" value="{{ $item->nombre }}" required />
                </div>

                <div>
                    <x-input-label value="Estado Operativo" />
                    <select name="activo" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 text-sm">
                        <option value="1" {{ old('activo', '1') == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>Inactivo / Mantenimiento</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="bg-green-600">Actualizar Tipo de Equipo</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-ui.confirm-deletion-modal 
    :id="$item->id"
    :title="'¿Eliminar Tipo de Equipo?'"
    :itemName="$item->nombre"
    :type="'el tipo de equipo'"
    :route="route('clients.equipos.destroy', $item)"
    />
    @endforeach