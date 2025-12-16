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

            {{-- Toolbar --}}
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

                <form method="GET" class="w-full md:w-2/3 space-y-3">
                    <div class="flex gap-2 items-center">

                        {{-- Buscador --}}
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar tipo de documento..."
                               class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                        <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            <x-heroicon-s-magnifying-glass class="w-5 h-5" />
                        </button>

                        {{-- Dropdown filtros (Se mantiene la misma lógica) --}}
                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click="open = !open"
                                     class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-100">
                                <x-heroicon-s-funnel class="w-4 h-4" />
                                Filtros
                                <x-heroicon-s-chevron-down class="w-4 h-4" />
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition
                                 class="absolute right-0 z-20 mt-2 w-72 bg-white border border-gray-200 rounded-lg shadow-lg p-4 space-y-4">

                                {{-- Estado --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                                    <select name="estado" class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Todos</option>
                                        <option value="activo" {{ $estado === 'activo' ? 'selected' : '' }}>Activos</option>
                                        <option value="inactivo" {{ $estado === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                                    </select>
                                </div>

                                {{-- Acciones --}}
                                <div class="flex justify-end gap-2 pt-2 border-t">
                                    <a href="{{ route('configuration.documentos.index') }}"
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-100">
                                        Limpiar
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        Aplicar filtros
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Acciones --}}
                <div class="flex gap-2 self-start md:self-center">
                    
                    {{-- PAPELERA --}}
                    <a href="{{ route('configuration.documentos.eliminados') }}"
                    class="inline-flex items-center px-4 py-2
                            border border-gray-300 rounded-md
                            text-sm font-medium text-gray-700
                            bg-white hover:bg-gray-100
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                        <x-heroicon-s-trash class="w-5 h-5 mr-2" />
                        Papelera
                    </a>

                    {{-- BOTÓN NUEVO --}}
                    <x-primary-button
                        class="bg-green-600 hover:bg-green-700 self-start md:self-center"
                        x-data
                        x-on:click="$dispatch('open-modal', 'crear-documento')">
                        <x-heroicon-s-plus class="w-5 h-5 mr-2" />
                        Nuevo Documento
                    </x-primary-button>
                </div>
            </div>

            {{-- TABLA RESPONSIVA --}}
            <x-data-table
                :items="$tipoDocumento"
                :headers="['Nombre', 'Estado', 'Creado', 'Actualizado']">

                @forelse($tipoDocumento as $documento)
                    {{-- Fila responsiva: Card en móvil, Fila de tabla en md+ --}}
                    <tr class="block md:table-row hover:bg-gray-50 transition duration-150 p-4 border-b border-gray-200 md:border-b-0">

                        {{-- COLUMNA 1: NOMBRE + ESTADO EN MÓVIL --}}
                        <td class="block md:table-cell px-6 py-4 text-sm text-gray-600 w-full md:w-4/12">
                            <div class="font-bold text-gray-900 text-base mb-1 md:font-normal md:text-sm flex items-center gap-2">
                                {{ $documento->nombre }}
                                {{-- Estado visible en móvil, oculto en desktop (Se muestra en su columna dedicada) --}}
                                <span class="md:hidden">
                                    @if($documento->estado)
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Activo</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">Inactivo</span>
                                    @endif
                                </span>
                            </div>
                        </td>

                        {{-- COLUMNA 2: ESTADO (Oculto en móvil, visible en md+) --}}
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600 w-2/12">
                            @if($documento->estado)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Activo</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">Inactivo</span>
                            @endif
                        </td>
                        
                        {{-- COLUMNA 3: CREADO (Oculto en móvil, visible en md+) --}}
                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-gray-600 w-2/12">
                            {{ $documento->created_at->format('d/m/Y') }}
                        </td>

                        {{-- COLUMNA 4: ACTUALIZADO (Oculto en móvil, visible en md+) --}}
                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-gray-600 w-2/12">
                            {{ $documento->updated_at->format('d/m/Y') }}
                        </td>

                        {{-- COLUMNA 5: ACCIONES (Visible en móvil y desktop) --}}
                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap text-sm font-medium w-full md:w-2/12">
                            <div class="flex gap-2 mt-2 md:mt-0">

                                {{-- TOGGLE ESTADO --}}
                                <form action="{{ route('configuration.documentos.toggle', $documento) }}" method="POST">
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
                                <button type="button" @click="$dispatch('open-modal', 'confirm-document-deletion-{{ $documento->id }}')" 
                                    title="Eliminar Documento"
                                    class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-100">
                                    <x-heroicon-s-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            No hay tipos de documento registrados.
                        </td>
                    </tr>
                @endforelse

            </x-data-table>
        </div>
    </div>

<x-modal name="crear-documento" :show="false" maxWidth="md">
        <form action="{{ route('configuration.documentos.store') }}" method="POST" class="p-6">
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

    @foreach($tipoDocumento as $documento)
    <x-modal name="edit-tipo-documento-{{ $documento->id }}" :show="false" maxWidth="md">
        <form method="POST" action="{{ route('configuration.documentos.update', $documento) }}" class="p-6">
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

                <x-primary-button class="bg-green-600 hover:bg-green-700 self-start md:self-center">
                    Guardar cambios
                </x-primary-button>
            </div>
        </form>
    </x-modal>
    @endforeach

    @foreach($tipoDocumento as $documento)
        <x-modal name="confirm-document-deletion-{{ $documento->id }}" :show="false" maxWidth="md">
            <form method="post" action="{{ route('configuration.documentos.destroy', $documento) }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    ¿Enviar documento a la papelera?
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    El estado
                    <span class="font-semibold text-gray-900">
                        {{ $documento->nombre }}
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
                        {{ __('Eliminar Documento') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-config-layout>