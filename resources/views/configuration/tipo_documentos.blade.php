<x-config-layout>

    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg p-6">

            {{-- MENSAJES --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- TÍTULO --}}
            <h2 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-3">
                Tipos de Documento
            </h2>

            {{-- TOOLBAR --}}
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

                {{-- BUSCADOR + FILTROS --}}
                <form method="GET" class="flex flex-col gap-3 w-full md:w-2/3">

                    {{-- BUSCADOR --}}
                    <div class="flex gap-2">
                        <input type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Buscar tipo de documento..."
                            class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                        <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            <x-heroicon-s-magnifying-glass class="w-5 h-5" />
                        </button>
                    </div>

                    {{-- FILTROS --}}
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-600">
                            Filtros:
                        </span>

                        <div class="flex gap-2 bg-gray-100 p-1 rounded-lg">
                            {{-- Todos --}}
                            <a href="{{ route('tipos-documentos.index') }}"
                            class="flex items-center gap-1 px-3 py-1 rounded-md text-sm
                                    {{ !$estado
                                            ? 'bg-indigo-600 text-white shadow'
                                            : 'text-gray-600 hover:bg-white' }}">
                                <x-heroicon-s-squares-2x2 class="w-4 h-4" />
                                Todos
                            </a>
                            {{-- Activos --}}
                            <a href="{{ route('tipos-documentos.index', ['estado' => 'activo']) }}"
                            class="flex items-center gap-1 px-3 py-1 rounded-md text-sm
                                    {{ $estado === 'activo'
                                            ? 'bg-green-600 text-white shadow'
                                            : 'text-gray-600 hover:bg-white' }}">
                                <x-heroicon-s-check-circle class="w-4 h-4" />
                                Activos
                            </a>

                            {{-- Inactivos --}}
                            <a href="{{ route('tipos-documentos.index', ['estado' => 'inactivo']) }}"
                            class="flex items-center gap-1 px-3 py-1 rounded-md text-sm
                                    {{ $estado === 'inactivo'
                                            ? 'bg-yellow-500 text-white shadow'
                                            : 'text-gray-600 hover:bg-white' }}">
                                <x-heroicon-s-pause-circle class="w-4 h-4" />
                                Inactivos
                            </a>
                        </div>
                    </div>
                </form>

                {{-- BOTÓN NUEVO --}}
                <x-primary-button
                    class="bg-green-600 hover:bg-green-700 self-start md:self-center"
                    x-data
                    x-on:click="$dispatch('open-modal', 'crear-documento')">
                    + Nuevo Documento
                </x-primary-button>
            </div>


            {{-- TABLA --}}
            <x-data-table
                :items="$tipoDocumento"
                :headers="['Nombre', 'Estado', 'Creado', 'Actualizado']">

                @forelse($tipoDocumento as $documento)
                    <tr class="hover:bg-gray-50">

                        {{-- NOMBRE --}}
                        <td class="px-6 py-4">
                            {{ $documento->nombre }}
                        </td>

                        {{-- ESTADO --}}
                        <td class="px-6 py-4">
                            @if($documento->estado)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">
                                    Activo
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">
                                    Inactivo
                                </span>
                            @endif
                        </td>
                        

                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                            {{ $documento->created_at->format('d/m/Y') }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                            {{ $documento->updated_at->format('d/m/Y') }}
                        </td>

                        {{-- ACCIONES --}}
                        <td class="px-6 py-4">
                            <div class="flex gap-2">

                                {{-- TOGGLE ESTADO --}}
                                <form action="{{ route('tipos-documentos.toggle', $documento) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button class="text-sm px-3 py-1 rounded
                                        {{ $documento->estado ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $documento->estado ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>


                                
                                {{-- BOTÓN EDITAR --}}
                                @if($documento->estado)
                                    <button
                                        type="button"
                                        @click="$dispatch('open-modal', 'edit-tipo-documento-{{ $documento->id }}')"
                                        title="Editar nombre"
                                        class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-100">
                                        <x-heroicon-s-pencil class="w-5 h-5" />
                                    </button>
                                @endif

                                {{-- ELIMINAR --}}
                                <form action="{{ route('tipos-documentos.destroy', $documento) }}" method="POST" class="inline-block" x-data>
                                    @csrf @method('DELETE')
                                    <button type="button" @click="$dispatch('open-modal', 'confirm-document-deletion-{{ $documento->id }}')" 
                                        title="Eliminar Documento"
                                        class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-100">
                                        <x-heroicon-s-trash class="w-5 h-5" />
                                    </button>
                                </form>




                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-6 text-gray-500">
                            No hay tipos de documento registrados.
                        </td>
                    </tr>
                @endforelse

            </x-data-table>

            {{-- PAGINACIÓN --}}
            <div class="mt-6">
                {{ $tipoDocumento->links() }}
            </div>

        </div>
    </div>

<!-- MODALES -->
    <!-- Modal para crear -->
    <x-modal name="crear-documento" :show="false" maxWidth="md">
        <form action="{{ route('tipos-documentos.store') }}" method="POST" class="p-6">
            @csrf

            {{-- Título --}}
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Crear nuevo tipo de documento') }}
            </h2>

            {{-- Input --}}
            <div class="mt-4">
                <x-input-label for="nombre" value="Nombre del documento" />
                <x-text-input
                    id="nombre"
                    name="nombre"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Nuevo documento"
                    required
                />
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>

            {{-- Botones --}}
            <div class="mt-6 flex justify-end">
                {{-- Cancelar --}}
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                {{-- Guardar --}}
                <x-primary-button class="ms-3 bg-green-600 hover:bg-green-700">
                    {{ __('Agregar') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    @if ($errors->any())
        <script>
            window.addEventListener('load', () => {
                window.dispatchEvent(
                    new CustomEvent('open-modal', {
                        detail: 'crear-documento'
                    })
                )
            })
        </script>
    @endif

    <!-- Modal de editar -->
    @foreach($tipoDocumento as $documento)
    <x-modal name="edit-tipo-documento-{{ $documento->id }}" :show="false" maxWidth="md">
        <form method="POST" action="{{ route('tipos-documentos.update', $documento) }}" class="p-6">
            @csrf
            @method('PUT')

            {{-- TÍTULO --}}
            <h2 class="text-lg font-medium text-gray-900">
                Editar Tipo de Documento
            </h2>

            {{-- DESCRIPCIÓN --}}
            <p class="mt-1 text-sm text-gray-600">
                Modifica el nombre del tipo de documento.
            </p>

            {{-- INPUT --}}
            <div class="mt-4">
                <x-input-label for="nombre-{{ $documento->id }}" value="Nombre" />
                
                <x-text-input
                    id="nombre-{{ $documento->id }}"
                    name="nombre"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ old('nombre', $documento->nombre) }}"
                    required
                    autofocus
                />

                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>

            {{-- BOTONES --}}
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>

                <x-primary-button>
                    Guardar cambios
                </x-primary-button>
            </div>
        </form>
    </x-modal>
    @endforeach

    <!-- Modal de eliminar -->
    @foreach($tipoDocumento as $documento)
        <x-modal name="confirm-document-deletion-{{ $documento->id }}" :show="false" maxWidth="md">
            <form method="post" action="{{ route('tipos-documentos.destroy', $documento) }}" class="p-6">
                @csrf
                @method('delete')

                {{-- Título y Mensaje de Advertencia --}}
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('¿Estás seguro de que quieres eliminar a este usuario?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Esta acción es irreversible. Estás a punto de eliminar al usuario: ') }}
                    <span class="font-bold text-red-600">{{ $documento->nombre }}</span>.
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
                        {{ __('Eliminar Documento') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-config-layout>
