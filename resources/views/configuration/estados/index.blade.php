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
                Estados de Clientes
            </h2>

            {{-- Toolbar --}}
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

                <form method="GET" class="w-full md:w-2/3 space-y-3">
                    <div class="flex gap-2 items-center">

                        {{-- Buscador --}}
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar estado..."
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
                                    <a href="{{ route('configuration.estados.index') }}"
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
                    <a href="{{ route('configuration.estados.eliminados') }}"
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
                        x-on:click="$dispatch('open-modal', 'crear-estado')">
                        <x-heroicon-s-plus class="w-5 h-5 mr-2" />
                        Nuevo Estado
                    </x-primary-button>
                </div>
            </div>

            {{-- TABLA RESPONSIVA --}}
            <x-data-table
                :items="$estados"
                :headers="['Nombre Estado', 'Preview', 'Estado', 'Creado', 'Actualizado']">

                @forelse($estados as $estado)
                    {{-- Fila responsiva: Card en móvil, Fila de tabla en md+ --}}
                    <tr class="block md:table-row hover:bg-gray-50 transition duration-150 p-4 border-b border-gray-200 md:border-b-0">

                        {{-- COLUMNA 1: NOMBRE + ESTADO EN MÓVIL --}}
                        <td class="block md:table-cell px-6 py-4 text-sm text-gray-600 w-full md:w-4/12">
                            <div class="font-bold text-gray-900 text-base mb-1 md:font-normal md:text-sm flex items-center gap-2">
                                {{ $estado->nombre }}

                                {{-- Estado visible en móvil, oculto en desktop (Se muestra en su columna dedicada) --}}
                                <span class="md:hidden">
                                    @if($estado->estado)
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Activo</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">Inactivo</span>
                                    @endif
                                </span>
                            </div>
                        </td>

                        {{-- COLUMNA 2: Preview del estado (Oculto en móvil, visible en md+) --}}
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600 w-3/12">
                            <span class="px-3 py-1 text-sm rounded transition duration-300 {{ $estado->clase_fondo }} {{ $estado->clase_texto }}">
                                Preview Estado
                            </span>
                        </td>

                        {{-- COLUMNA 3: ESTADO (Oculto en móvil, visible en md+) --}}
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600 w-2/12">
                            @if($estado->estado)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Activo</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">Inactivo</span>
                            @endif
                        </td>

                        {{-- COLUMNA 4: CREADO (Oculto en móvil, visible en md+) --}}
                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-gray-600 w-2/12">
                            {{ $estado->created_at->format('d/m/Y') }}
                        </td>

                        {{-- COLUMNA 5: ACTUALIZADO (Oculto en móvil, visible en md+) --}}
                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-gray-600 w-2/12">
                            {{ $estado->updated_at->format('d/m/Y') }}
                        </td>

                        {{-- COLUMNA 6: ACCIONES (Visible en móvil y desktop) --}}
                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap text-sm font-medium w-full md:w-2/12">
                            <div class="flex gap-2 mt-2 md:mt-0">

                                {{-- TOGGLE ESTADO --}}
                                <form action="{{ route('configuration.estados.toggle', $estado) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm px-3 py-1 rounded
                                        {{ $estado->estado ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $estado->estado ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>

                                {{-- BOTÓN EDITAR --}}
                                @if($estado->estado)
                                    <button
                                        type="button"
                                        @click="$dispatch('open-modal', 'edit-estado-{{ $estado->id }}')"
                                        title="Editar nombre"
                                        class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-100">
                                        <x-heroicon-s-pencil class="w-5 h-5" />
                                    </button>
                                @endif

                                {{-- ELIMINAR --}}
                                <button type="button" @click="$dispatch('open-modal', 'confirm-estatus-deletion-{{ $estado->id }}')" 
                                    title="Eliminar Estado"
                                    class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-100">
                                    <x-heroicon-s-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            No hay estados de clientes registrados.
                        </td>
                    </tr>
                @endforelse

            </x-data-table>
        </div>
    </div>

<x-modal name="crear-estado" :show="false" maxWidth="md">
        <form action="{{ route('configuration.estados.store') }}" method="POST" class="p-6">
            @csrf

            {{-- Título --}}
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Crear nuevo estado') }}
            </h2>

            {{-- Input --}}
            <div class="mt-4">
                <x-input-label for="nombre" value="Nombre del Estado" />
                <x-text-input
                    id="nombre"
                    name="nombre"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Nuevo estado"
                    required
                />
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>

            <div class="mt-4" x-data="{ selectedColor: 'gray' }">
                <x-input-label for="color_base" value="Color para el Badge (Activo)" />
                
                <div class="flex flex-wrap gap-3 mt-1">
                    @php
                        $colors = ['green', 'indigo', 'red', 'yellow', 'gray', 'blue', 'purple'];
                    @endphp

                    @foreach($colors as $color)
                        <label for="color-{{ $color }}" class="flex flex-col items-center cursor-pointer">
                            <input type="radio" 
                                id="color-{{ $color }}" 
                                name="color_base" 
                                value="{{ $color }}" 
                                class="hidden" 
                                x-model="selectedColor">
                            
                            <span class="w-8 h-8 rounded-full 
                                bg-{{ $color }}-600 
                                border-2 
                                shadow-md 
                                transition duration-150 ease-in-out"
                                :class="{ 'ring-4 ring-offset-2 ring-{{ $color }}-500': selectedColor === '{{ $color }}' }"
                                title="{{ ucfirst($color) }}">
                            </span>
                        </label>
                    @endforeach
                </div>
                
                <x-input-error :messages="$errors->get('color_base')" class="mt-2" />

                {{-- Vista Previa (Preview) --}}
                <div class="mt-4">
                    <x-input-label value="Vista Previa del Estado Activo:" class="mb-2"/>
                    <span class="px-3 py-1 text-sm rounded transition duration-300" 
                        :class="`bg-${selectedColor}-100 text-${selectedColor}-800`">
                        Estado de Cliente Activo
                    </span>
                </div>
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
                        detail: 'crear-estado'
                    })
                )
            })
        </script>
    @endif

    @foreach($estados as $estado)
    <x-modal name="edit-estado-{{ $estado->id }}" :show="false" maxWidth="md">
        <form method="POST" action="{{ route('configuration.estados.update', $estado) }}" class="p-6">
            @csrf
            @method('PUT')

            {{-- TÍTULO --}}
            <h2 class="text-lg font-medium text-gray-900">
                Editar Estado de ciente
            </h2>

            {{-- DESCRIPCIÓN --}}
            <p class="mt-1 text-sm text-gray-600">
                Modifica el nombre del estado de cliente.
            </p>

            {{-- INPUT --}}
            <div class="mt-4">
                <x-input-label for="nombre-{{ $estado->id }}" value="Nombre" />
                
                <x-text-input
                    id="nombre-{{ $estado->id }}"
                    name="nombre"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ old('nombre', $estado->nombre) }}"
                    required
                    autofocus
                />

                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>

            @php
                // Extraer el color base de la clase_fondo. Ej: de 'bg-green-100' a 'green'
                $currentColorClass = explode('-', $estado->clase_fondo)[1] ?? 'gray';
                $colors = ['green', 'indigo', 'red', 'yellow', 'gray', 'blue', 'purple'];
            @endphp

            <div class="mt-4" x-data="{ selectedColor: '{{ $currentColorClass }}' }">
                <x-input-label for="color_base" value="Color para el Badge (Activo)" />
                
                <div class="flex flex-wrap gap-3 mt-1">
                    @foreach($colors as $color)
                        <label for="edit-color-{{ $estado->id }}-{{ $color }}" class="flex flex-col items-center cursor-pointer">
                            <input type="radio" 
                                id="edit-color-{{ $estado->id }}-{{ $color }}" 
                                name="color_base" 
                                value="{{ $color }}" 
                                class="hidden" 
                                x-model="selectedColor"
                                {{ $currentColorClass === $color ? 'checked' : '' }}>
                            
                            <span class="w-8 h-8 rounded-full 
                                bg-{{ $color }}-600 
                                border-2 
                                shadow-md 
                                transition duration-150 ease-in-out"
                                :class="{ 'ring-4 ring-offset-2 ring-{{ $color }}-500': selectedColor === '{{ $color }}' }"
                                title="{{ ucfirst($color) }}">
                            </span>
                        </label>
                    @endforeach
                </div>
                
                <x-input-error :messages="$errors->get('color_base')" class="mt-2" />

                {{-- Vista Previa (Preview) --}}
                <div class="mt-4">
                    <x-input-label value="Vista Previa del Estado Activo:" class="mb-2"/>
                    <span class="px-3 py-1 text-sm rounded transition duration-300" 
                        :class="`bg-${selectedColor}-100 text-${selectedColor}-800`">
                        Estado de Cliente Activo
                    </span>
                </div>
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

    @foreach($estados as $estado)
        <x-modal name="confirm-estatus-deletion-{{ $estado->id }}" :show="false" maxWidth="md">
            <form method="post" action="{{ route('configuration.estados.destroy', $estado) }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    ¿Enviar provincia a la papelera?
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    El estado
                    <span class="font-semibold text-gray-900">
                        {{ $estado->nombre }}
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
                        {{ __('Eliminar estado') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-config-layout>