<x-config-layout>
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg p-6">
            
            <div class="fixed top-4 right-4 z-50 flex flex-col gap-4 w-full max-w-sm px-4 md:px-0">
                {{-- TOAST DE ÉXITO --}}
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-8"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        x-init="setTimeout(() => show = false, 5000)"
                        class="overflow-hidden rounded-lg shadow-2xl border border-emerald-600">
                        {{-- Cabecera del Toast (Verde Oscuro) --}}
                        <div class="bg-emerald-600 px-4 py-2 flex justify-between items-center">
                            <span class="text-white font-bold text-sm">Configuración actualizada</span>
                            <div class="flex items-center gap-2">
                                <span class="text-emerald-100 text-xs font-medium">Éxito</span>
                                <button @click="show = false" class="text-white hover:text-emerald-200 transition-colors">
                                    <x-heroicon-s-x-mark class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                        {{-- Cuerpo del Toast (Verde Claro) --}}
                        <div class="bg-emerald-500 px-4 py-3">
                            <p class="text-white text-sm leading-relaxed">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                @endif

                {{-- TOAST DE ERROR --}}
                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-8"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-init="setTimeout(() => show = false, 6000)"
                        class="overflow-hidden rounded-lg shadow-2xl border border-red-600">
                        <div class="bg-red-600 px-4 py-2 flex justify-between items-center">
                            <span class="text-white font-bold text-sm">Error en el sistema</span>
                            <div class="flex items-center gap-2">
                                <span class="text-red-100 text-xs font-medium">Alerta</span>
                                <button @click="show = false" class="text-white hover:text-red-200">
                                    <x-heroicon-s-x-mark class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                        <div class="bg-red-500 px-4 py-3">
                            <p class="text-white text-sm leading-relaxed">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <h2 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-3">Estados de Clientes</h2>

            {{-- Toolbar --}}
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
                <form method="GET" class="w-full md:w-2/3 flex gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Buscar estado..."
                           class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        <x-heroicon-s-magnifying-glass class="w-5 h-5" />
                    </button>
                    
                    <select name="estado" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm">
                        <option value="">Todos</option>
                        <option value="activo" {{ $estadoFiltro === 'activo' ? 'selected' : '' }}>Activos</option>
                        <option value="inactivo" {{ $estadoFiltro === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </form>

                <div class="flex gap-2">
                    <a href="{{ route('configuration.estados.eliminados') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-100">
                        <x-heroicon-s-trash class="w-5 h-5 mr-2" /> Papelera
                    </a>
                    <x-primary-button class="bg-green-600 hover:bg-green-700" x-data x-on:click="$dispatch('open-modal', 'crear-estado')">
                        <x-heroicon-s-plus class="w-5 h-5 mr-2" /> Nuevo
                    </x-primary-button>
                </div>
            </div>

            {{-- TABLA RESPONSIVA --}}
            <x-data-table
                :items="$estados"
                :headers="['Nombre', 'Permisos', 'Preview', 'Catálogo']">

                @forelse($estados as $estado)
                    <tr class="block md:table-row hover:bg-gray-50 transition duration-150 p-4 border-b border-gray-200 md:border-b-0">
                        
                        {{-- COLUMNA 1: NOMBRE + BADGE EN MÓVIL --}}
                        <td class="block md:table-cell px-6 py-4 text-sm text-gray-600 w-full md:w-3/12">
                            <div class="font-bold text-gray-900 text-base mb-1 md:font-normal md:text-sm flex items-center gap-2">
                                {{ $estado->nombre }}
                                <span class="md:hidden">
                                    <span class="px-2 py-1 text-xs rounded font-bold {{ $estado->clase_fondo }} {{ $estado->clase_texto }}">
                                        {{ $estado->nombre }}
                                    </span>
                                </span>
                            </div>
                        </td>
                        
                        <td class="hidden md:table-cell px-6 py-4 w-2/12">
                            <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
                                <x-heroicon-s-tag class="w-3.5 h-3.5" />
                                {{ $estado->categoria->name ?? 'Sin categoría' }}
                            </span>
                        </td>

                        {{-- COLUMNA 3: PREVIEW (Oculto en móvil) --}}
                        <td class="hidden md:table-cell px-6 py-4 w-2/12">
                            <span class="px-2 py-1 text-xs rounded font-bold {{ $estado->clase_fondo }} {{ $estado->clase_texto }}">
                                {{ $estado->nombre }}
                            </span>
                        </td>

                        {{-- COLUMNA 4: CATÁLOGO (Oculto en móvil) --}}
                        <td class="hidden md:table-cell px-6 py-4 w-2/12">
                            @if($estado->activo)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full font-semibold">Activo</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full font-semibold">Inactivo</span>
                            @endif
                        </td>

                        {{-- COLUMNA 5: ACCIONES --}}
                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap text-sm font-medium w-full md:w-3/12">
                            <div class="flex items-center gap-3 mt-2 md:mt-0">
                                @php 
                                    $isLastActives = $estado->activo && $estados->where('activo', true)->count() <= 2; 
                                @endphp

                                <form action="{{ route('configuration.estados.toggle', $estado) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" 
                                        {{ $isLastActives ? 'disabled' : '' }}
                                        class="text-xs px-2 py-1 rounded border {{ $isLastActives ? 'bg-gray-50 text-gray-400 border-gray-200 cursor-not-allowed' : ($estado->activo ? 'bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100' : 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100') }}">
                                        {{ $isLastActives ? 'Mínimo Activos' : ($estado->activo ? 'Desactivar' : 'Activar') }}
                                    </button>
                                </form>

                                <button @click="$dispatch('open-modal', 'edit-estado-{{ $estado->id }}')" class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50">
                                    <x-heroicon-s-pencil class="w-5 h-5" />
                                </button>

                                <button 
                                    @if(!$isLastActives) @click="$dispatch('open-modal', 'confirm-deletion-{{ $estado->id }}')" @endif
                                    class="p-1 rounded {{ $isLastActives ? 'text-gray-300 cursor-not-allowed' : 'text-red-600 hover:text-red-900 hover:bg-red-50' }}"
                                    {{ $isLastActives ? 'title=No_puedes_eliminar_un_estado_activo_minimo' : '' }}>
                                    <x-heroicon-s-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-10 text-gray-500">No hay registros.</td></tr>
                @endforelse
            </x-data-table>
        </div>
    </div>
    {{-- MODAL CREAR --}}
    <x-modal name="crear-estado" maxWidth="md">
        <form action="{{ route('configuration.estados.store') }}" method="POST" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900 mb-4">Nuevo Estado de Cliente</h2>
            
            <div class="space-y-4">
                <div>
                    <x-input-label for="nombre" value="Nombre del Estado" />
                    <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" required />
                </div>

                <div>
                    <x-input-label value="Categoría del Estado" />
                    <select name="client_state_category_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Seleccione una categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">
                                {{ $categoria->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div x-data="{ selectedColor: 'gray' }">
                    <x-input-label value="Color del Badge" class="mb-2" />
                    <div class="flex flex-wrap gap-2">
                        @foreach(['green', 'indigo', 'red', 'yellow', 'gray', 'blue', 'purple'] as $color)
                            <label class="cursor-pointer">
                                <input type="radio" name="color_base" value="{{ $color }}" class="hidden" x-model="selectedColor">
                                <span class="w-8 h-8 rounded-full block border-2 transition shadow-sm"
                                      :class="selectedColor === '{{ $color }}' ? 'ring-2 ring-offset-1 ring-{{ $color }}-500 border-white bg-{{ $color }}-600' : 'bg-{{ $color }}-600 border-transparent hover:scale-105'"></span>
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-4 p-4 border border-dashed rounded-lg text-center bg-gray-50">
                        <span :class="`bg-${selectedColor}-100 text-${selectedColor}-800`" class="px-4 py-1.5 rounded text-sm font-bold uppercase tracking-wider shadow-sm">
                            Vista Previa
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="bg-green-600">Guardar Estado</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- MODAL EDITAR --}}
    @foreach($estados as $estado)
    <x-modal name="edit-estado-{{ $estado->id }}" maxWidth="md">
        <form method="POST" action="{{ route('configuration.estados.update', $estado) }}" class="p-6">
            @csrf @method('PUT')
            <h2 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Editar: {{ $estado->nombre }}</h2>

            <div class="space-y-4">
                <div>
                    <x-input-label value="Nombre" />
                    <x-text-input name="nombre" type="text" class="mt-1 block w-full" value="{{ $estado->nombre }}" required />
                </div>

                <div>
                    <x-input-label value="Categoría del Estado" />
                    <select name="client_state_category_id" required
                        class="mt-1 block w-full rounded-md border-gray-300">
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                {{ $estado->client_state_category_id === $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                @php $currentColor = explode('-', $estado->clase_fondo)[1] ?? 'gray'; @endphp
                <div x-data="{ selectedColor: '{{ $currentColor }}' }">
                    <x-input-label value="Color del Badge" class="mb-2" />
                    <div class="flex flex-wrap gap-2">
                        @foreach(['green', 'indigo', 'red', 'yellow', 'gray', 'blue', 'purple'] as $color)
                            <label class="cursor-pointer">
                                <input type="radio" name="color_base" value="{{ $color }}" class="hidden" x-model="selectedColor">
                                <span class="w-8 h-8 rounded-full block border-2 transition shadow-sm"
                                      :class="selectedColor === '{{ $color }}' ? 'ring-2 ring-offset-1 ring-{{ $color }}-500 border-white bg-{{ $color }}-600' : 'bg-{{ $color }}-600 border-transparent hover:scale-105'"></span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="bg-indigo-600">Actualizar</x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- MODAL ELIMINAR --}}
    <x-modal name="confirm-deletion-{{ $estado->id }}" maxWidth="md">
        <form method="post" action="{{ route('configuration.estados.destroy', $estado) }}" class="p-6">
            @csrf @method('delete')
            <h2 class="text-lg font-medium text-gray-900">
                ¿Enviar Estado de cliente a la papelera?
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
                    {{ __('Eliminar Estado de cliente') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
    @endforeach

</x-config-layout>