<x-app-layout>

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
                Provincias
            </h2>

            {{-- TOOLBAR --}}
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

                {{-- BUSCADOR + FILTROS --}}
                {{-- Nota: Se mantiene el w-full md:w-2/3 para que ocupe espacio, pero es un formulario simple --}}
                <form method="GET" class="w-full md:w-2/3 space-y-3">
                    <div class="flex gap-2 items-center">

                        {{-- Buscador --}}
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar provincia..."
                               class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                        <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            <x-heroicon-s-magnifying-glass class="w-5 h-5" />
                        </button>

                        {{-- Dropdown filtros (Mantenemos la estructura) --}}
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
                                    <select name="estado"
                                            class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Todos</option>
                                        <option value="activo" {{ $estado === 'activo' ? 'selected' : '' }}>Activos</option>
                                        <option value="inactivo" {{ $estado === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                                    </select>
                                </div>

                                {{-- Acciones --}}
                                <div class="flex justify-end gap-2 pt-2 border-t">
                                    <a href="{{ route('provincias.index') }}"
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

                {{-- ACCIONES --}}
                <div class="flex gap-2 self-start md:self-center">

                    {{-- PAPELERA --}}
                    <a href="{{ route('provincias.eliminadas') }}"
                    class="inline-flex items-center px-4 py-2
                            border border-gray-300 rounded-md
                            text-sm font-medium text-gray-700
                            bg-white hover:bg-gray-100
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                        <x-heroicon-s-trash class="w-5 h-5 mr-2" />
                        Papelera
                    </a>

                    {{-- NUEVO --}}
                    <a href="{{ route('provincias.create') }}"
                    class="inline-flex items-center px-4 py-2
                            bg-green-600 text-white rounded-md
                            hover:bg-green-700
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <x-heroicon-s-plus class="w-5 h-5 mr-2" />
                        Nueva Provincia
                    </a>

                </div>

            </div>

            {{-- TABLA RESPONSIVA --}}
            <x-data-table
                :items="$provincias"
                :headers="['Nombre', 'Estado', 'Creado', 'Actualizado']"> {{-- Añadí 'Acciones' para que el header sea consistente --}}

                @forelse($provincias as $provincia)
                    {{-- La magia responsiva: block en móvil, table-row en md+ --}}
                    <tr class="block md:table-row hover:bg-gray-50 transition duration-150 p-4 border-b border-gray-200 md:border-b-0">

                        {{-- COLUMNA 1: NOMBRE + ESTADO EN MÓVIL --}}
                        <td class="block md:table-cell px-6 py-4 text-sm text-gray-600 w-full md:w-4/12">
                            <div class="font-bold text-gray-900 text-base mb-1 md:font-normal md:text-sm flex items-center gap-2">
                                {{ $provincia->nombre }}
                                {{-- Estado visible en móvil, oculto en desktop (Se muestra en su columna dedicada) --}}
                                <span class="md:hidden">
                                    @if($provincia->estado)
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Activo</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">Inactivo</span>
                                    @endif
                                </span>
                            </div>
                        </td>

                        {{-- COLUMNA 2: ESTADO (Visible en tablet/desktop) --}}
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600">
                            <span class="md:hidden font-bold text-gray-700 mr-2">Estado:</span>
                            @if($provincia->estado)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">
                                    Activo
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">
                                    Inactivo
                                </span>
                            @endif
                        </td>

                        {{-- COLUMNA 3: CREADO (Oculto en tablet, visible en desktop grande) --}}
                        <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $provincia->created_at->format('d/m/Y') }}
                        </td>

                        {{-- COLUMNA 4: ACTUALIZADO (Oculto en tablet, visible en desktop grande) --}}
                        <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $provincia->updated_at->format('d/m/Y') }}
                        </td>

                        {{-- COLUMNA 5: ACCIONES (Visible en móvil y desktop) --}}
                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-2">

                                {{-- TOGGLE ESTADO --}}
                                <form action="{{ route('provincias.toggle', $provincia) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm px-3 py-1 rounded
                                        {{ $provincia->estado ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $provincia->estado ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>

                                {{-- EDITAR --}}
                                <a href="{{ route('provincias.edit', $provincia) }}"
                                   class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-100">
                                    <x-heroicon-s-pencil class="w-5 h-5" />
                                </a>

                                {{-- ELIMINAR --}}
                                <button
                                    x-data
                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-{{ $provincia->id }}')"
                                    class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-100"
                                    title="Enviar a papelera">
                                    <x-heroicon-s-trash class="w-5 h-5" />
                                </button>


                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            No hay provincias registradas.
                        </td>
                    </tr>
                @endforelse
            </x-data-table>
        </div>
    </div>

{{-- MODAL DE ADVERTENCIA --}}
@foreach($provincias as $provincia)
    <x-modal name="confirm-delete-{{ $provincia->id }}" :show="false" maxWidth="md">
        <form action="{{ route('provincias.destroy', $provincia) }}" method="POST" class="p-6">
            @csrf
            @method('DELETE')

            <h2 class="text-lg font-medium text-gray-900">
                ¿Enviar provincia a la papelera?
            </h2>

            <p class="mt-2 text-sm text-gray-600">
                La provincia
                <span class="font-semibold text-gray-900">
                    {{ $provincia->nombre }}
                </span>
                será movida a la
                <span class="font-semibold text-yellow-600">papelera</span>.
            </p>

            <p class="mt-1 text-sm text-gray-500">
                Esta acción se puede revertir desde la papelera.
            </p>

            <div class="mt-6 flex justify-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>

                <x-danger-button>
                    <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                    Enviar a papelera
                </x-danger-button>
            </div>
        </form>
    </x-modal>
@endforeach

</x-app-layout>