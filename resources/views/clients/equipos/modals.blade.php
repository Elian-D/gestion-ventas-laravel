    {{-- MODAL CREAR --}}
    <x-modal name="crear-tipoEquipo" maxWidth="md">
        <form action="{{ route('clients.equipos.store') }}" method="POST" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 mb-4">Nuevo Tipo de Equipo</h2>

            <div class="space-y-4">
                <div>
                    <x-input-label for="nombre" value="Nombre del Tipo de Equipo" />
                    <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" required />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="bg-green-600">Guardar Tipo de Equipo</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- MODAL EDITAR --}}
    @foreach($tipoEquipo as $equipo)
    <x-modal name="edit-tipoEquipo-{{ $equipo->id }}" maxWidth="md">
        <form method="POST" action="{{ route('clients.equipos.update', $equipo) }}" class="p-6">
            @csrf @method('PUT')
            <h2 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Editar: {{ $equipo->nombre }}</h2>

            <div class="space-y-4">
                <div>
                    <x-input-label value="Nombre" />
                    <x-text-input name="nombre" type="text" class="mt-1 block w-full" value="{{ $equipo->nombre }}" required />
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="bg-indigo-600">Actualizar</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- MODAL ELIMINAR --}}
    <x-modal name="confirm-deletion-{{ $equipo->id }}" maxWidth="md">
        <form method="post" action="{{ route('clients.equipos.destroy', $equipo) }}" class="p-6">
            @csrf @method('delete')
            <h2 class="text-lg font-medium text-gray-900">
                ¿Enviar Tipo de Equipo a la papelera?
            </h2>

            <p class="mt-2 text-sm text-gray-600">
                El tipo de equipo
                <span class="font-semibold text-gray-900">
                    {{ $equipo->nombre }}
                </span>
                será movida a la
                <span class="font-semibold text-yellow-600">papelera</span>.
            </p>

            <p class="mt-1 text-sm text-gray-500">
                Esta acción se puede revertir desde la papelera.
            </p>

            {{-- Área de Botones del Modal --}}
            <div class="mt-6 flex justify-end">
                
                {{-- Botón Cancelar --}}
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                {{-- Botón Eliminar (Rojo) --}}
                <x-danger-button class="ms-3">
                    <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                    {{ __('Eliminar Tipo de Equipo') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
    @endforeach