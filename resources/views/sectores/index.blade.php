<x-app-layout>

    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg p-6">

            {{-- Mensajes --}}
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

            {{-- Título --}}
            <h2 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-3">
                Gestión de Sectores
            </h2>

            {{-- Toolbar (Se mantiene responsivo como en la vista anterior) --}}
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

                <form method="GET" class="w-full md:w-2/3 space-y-3">
                    <div class="flex gap-2 items-center">

                        {{-- Buscador --}}
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar sector..."
                               class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                        <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            <x-heroicon-s-magnifying-glass class="w-5 h-5" />
                        </button>

                        {{-- Dropdown filtros (Contenido omitido por brevedad, se mantiene igual) --}}
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

                                {{-- Municipio --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
                                    <select name="municipio_id" class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Todos</option>
                                        @foreach($municipios as $municipio)
                                            <option value="{{ $municipio->id }}" {{ $municipio_id == $municipio->id ? 'selected' : '' }}>
                                                {{ $municipio->nombre }} ({{ $municipio->provincia->nombre ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Acciones --}}
                                <div class="flex justify-end gap-2 pt-2 border-t">
                                    <a href="{{ route('sectores.index') }}"
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
                    <a href="{{ route('sectores.eliminadas') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-100">
                        <x-heroicon-s-trash class="w-5 h-5 mr-2" />
                        Papelera
                    </a>
                    <a href="{{ route('sectores.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <x-heroicon-s-plus class="w-5 h-5 mr-2" />
                        Nuevo Sector
                    </a>
                </div>
            </div>

            {{-- TABLA RESPONSIVA --}}
            <x-data-table :items="$sectores" :headers="['Nombre', 'Municipio', 'Provincia', 'Estado', 'Creado', 'Actualizado']">
                @forelse($sectores as $sector)
                    {{-- Fila responsiva: Card en móvil, Fila de tabla en md+ --}}
                    <tr class="block md:table-row hover:bg-gray-50 transition duration-150 p-4 border-b border-gray-200 md:border-b-0">
                        
                        {{-- COLUMNA 1: NOMBRE + ESTADO EN MÓVIL --}}
                        <td class="block md:table-cell px-6 py-4 text-sm text-gray-600 w-full md:w-4/12">
                            <div class="font-bold text-gray-900 text-base mb-1 md:font-normal md:text-sm flex items-center gap-2">
                                {{ $sector->nombre }}
                                {{-- Estado visible en móvil, oculto en desktop (Se muestra en su columna dedicada) --}}
                                <span class="md:hidden">
                                    @if($sector->estado)
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Activo</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">Inactivo</span>
                                    @endif
                                </span>
                            </div>
                        </td>

                        {{-- COLUMNAS 2 & 3: Municipio + Provincia (Combinadas en una sola celda para móvil) --}}
                        {{-- OJO: En desktop, esta celda se usa para el Municipio, y la siguiente para la Provincia. --}}
                        {{-- Para simular las tres columnas en móvil, mostraremos los tres datos principales juntos. --}}
                        <td class="block md:table-cell px-6 py-4 text-sm text-gray-500 md:text-gray-600 w-full md:w-4/12">
                             <span class="md:hidden font-semibold text-gray-700 mr-2">Municipio:</span>
                            {{ $sector->municipio->nombre ?? '-' }}
                            {{-- Mostramos Provincia solo en móvil, ya que en desktop tiene su propia columna --}}
                            <span class="text-gray-400 md:hidden">
                                (Provincia: {{ $sector->municipio->provincia->nombre ?? '-' }})
                            </span>
                        </td>
                        
                        {{-- Columna 3 (Provincia original) - Oculta en móvil (xs), visible en md+ --}}
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600 w-2/12">
                            {{ $sector->municipio->provincia->nombre ?? '-' }}
                        </td>

                        {{-- COLUMNA 4: Estado (Oculto en móvil, visible en md+) --}}
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600 w-1/12">
                            @if($sector->estado)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Activo</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">Inactivo</span>
                            @endif
                        </td>
                        
                        {{-- COLUMNAS 5 & 6: Fechas (Ocultas en móvil/tablet, visibles en lg+) --}}
                        <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-gray-600 w-1/12">{{ $sector->created_at->format('d/m/Y') }}</td>
                        <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-gray-600 w-1/12">{{ $sector->updated_at->format('d/m/Y') }}</td>
                        
                        {{-- COLUMNA 7: Acciones (Visible en móvil y desktop) --}}
                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap text-sm font-medium w-full md:w-auto">
                            <div class="flex gap-2 mt-2 md:mt-0">
                                {{-- Toggle Estado --}}
                                <form action="{{ route('sectores.toggle', $sector) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm px-3 py-1 rounded {{ $sector->estado ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $sector->estado ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>

                                {{-- Editar --}}
                                <a href="{{ route('sectores.edit', $sector) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-100">
                                    <x-heroicon-s-pencil class="w-5 h-5" />
                                </a>

                                {{-- Papelera --}}
                                <button x-data x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-{{ $sector->id }}')" class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-100" title="Enviar a papelera">
                                    <x-heroicon-s-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        {{-- Ajustamos el colspan a 6 (Nombre, Municipio/Provincia, Estado, Creado, Actualizado, Acciones) --}}
                        <td colspan="6" class="text-center py-6 text-gray-500">No hay sectores registrados.</td>
                    </tr>
                @endforelse
            </x-data-table>
        </div>
    </div>

    {{-- Modal de papelera --}}
    @foreach($sectores as $sector)
        <x-modal name="confirm-delete-{{ $sector->id }}" :show="false" maxWidth="md">
            <form action="{{ route('sectores.destroy', $sector) }}" method="POST" class="p-6">
                @csrf
                @method('DELETE')
                <h2 class="text-lg font-medium text-gray-900">¿Enviar sector a la papelera?</h2>
                <p class="mt-2 text-sm text-gray-600">
                    El sector <span class="font-semibold text-gray-900">{{ $sector->nombre }}</span>
                    será movido a la <span class="font-semibold text-yellow-600">papelera</span>.
                </p>
                <p class="mt-1 text-sm text-gray-500">Esta acción se puede revertir desde la papelera.</p>
                <div class="mt-6 flex justify-end gap-2">
                    <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                    <x-danger-button>
                        <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                        Enviar a papelera
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach

</x-app-layout>