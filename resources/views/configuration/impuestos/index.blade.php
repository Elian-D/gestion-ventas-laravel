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
                Impuestos
            </h2>

            {{-- Toolbar --}}
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

                <form method="GET" class="w-full md:w-2/3 space-y-3">
                    <div class="flex gap-2 items-center">

                        {{-- Buscador --}}
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar impuestos..."
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
                                    <a href="{{ route('configuration.impuestos.index') }}"
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
                    <a href="{{ route('configuration.impuestos.eliminados') }}"
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
                        x-on:click="$dispatch('open-modal', 'crear-impuesto')">
                        <x-heroicon-s-plus class="w-5 h-5 mr-2" />
                        Nuevo Impuesto
                    </x-primary-button>
                </div>
            </div>

            {{-- TABLA RESPONSIVA --}}
            <x-data-table
                :items="$impuesto"
                :headers="['Nombre', 'Tipo', 'Valor', 'Incluido', 'Estado', 'Creado', 'Actualizado']"> {{-- Headers actualizados --}}

                @forelse($impuesto as $imp)
                    {{-- Fila responsiva: Card en móvil, Fila de tabla en md+ --}}
                    <tr class="hover:bg-gray-50 transition">

                        {{-- COLUMNA 1: NOMBRE + ESTADO EN MÓVIL --}}
                        <td class="block md:table-cell px-6 py-4 text-sm text-gray-600 w-full md:w-2/12">
                            <div class="font-bold text-gray-900 text-base mb-1 md:font-normal md:text-sm flex items-center gap-2">
                                {{ $imp->nombre }}
                                {{-- Estado visible en móvil, oculto en desktop (Se muestra en su columna dedicada) --}}
                                <span class="md:hidden">
                                    @if($imp->estado)
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Activo</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">Inactivo</span>
                                    @endif
                                </span>
                            </div>
                        </td>
                        
                        {{-- ** NUEVAS COLUMNAS ** --}}

                        {{-- COLUMNA 2: TIPO (Visible en móvil como etiqueta) --}}
                        <td class="block md:table-cell px-6 py-4 text-sm text-gray-600 w-full md:w-2/12">
                            <span class="md:hidden text-xs font-semibold text-gray-500 block mb-1">Tipo:</span>
                            <span class="capitalize">{{ $imp->tipo }}</span>
                        </td>

                        {{-- COLUMNA 3: VALOR (Visible en móvil como etiqueta) --}}
                        <td class="block md:table-cell px-6 py-4 text-sm text-gray-600 w-full md:w-2/12">
                            <span class="md:hidden text-xs font-semibold text-gray-500 block mb-1">Valor:</span>
                            {{ $imp->tipo === 'porcentaje' ? number_format($imp->valor, 2) . '%' : '$' . number_format($imp->valor, 2) }}
                        </td>
                        
                        {{-- COLUMNA 4: ES INCLUIDO (Visible en móvil como etiqueta) --}}
                        <td class="block md:table-cell px-6 py-4 text-sm text-gray-600 w-full md:w-1/12">
                            <span class="md:hidden text-xs font-semibold text-gray-500 block mb-1">Incluido:</span>
                            @if($imp->es_incluido)
                                <x-heroicon-s-check-circle class="w-5 h-5 text-green-500 mx-auto md:mx-0" title="Incluido en Precio" />
                            @else
                                <x-heroicon-s-x-circle class="w-5 h-5 text-red-500 mx-auto md:mx-0" title="No Incluido en Precio" />
                            @endif
                        </td>

                        {{-- ** FIN NUEVAS COLUMNAS ** --}}

                        {{-- COLUMNA 5: ESTADO (Oculto en móvil, visible en md+) --}}
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600 w-1/12">
                            @if($imp->estado)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Activo</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded">Inactivo</span>
                            @endif
                        </td>
                        
                        {{-- COLUMNA 6: CREADO --}}
                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-gray-600 w-1/12">
                            {{ $imp->created_at->format('d/m/Y') }}
                        </td>

                        {{-- COLUMNA 7: ACTUALIZADO --}}
                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-gray-600 w-1/12">
                            {{ $imp->updated_at->format('d/m/Y') }}
                        </td>

                        {{-- COLUMNA 8: ACCIONES --}}
                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap text-sm font-medium w-full md:w-2/12">
                            <div class="flex gap-2 mt-2 md:mt-0">


                                {{-- TOGGLE ESTADO CON VALIDACIÓN DE ÚLTIMO ACTIVO --}}
                                @php
                                    // Asumiendo que has pasado el conteo total de activos desde el controlador (ej: $totalActivos)
                                    // Si no, tendrás que recalcularlo o simplificar la validación.
                                    // Para este ejemplo, simularé la lógica directamente.
                                    $soloUnActivo = $impuesto->count() === 1 && $imp->estado; // ESTO ES UNA SIMULACIÓN RÚSTICA, DEBES USAR UN CONTEO TOTAL REAL
                                    $totalActivos = $impuesto->where('estado', true)->count();
                                    $esUltimoActivo = $imp->estado && $totalActivos === 1;
                                @endphp
                                
                                @if (!$imp->estado || !$esUltimoActivo)
                                    <form action="{{ route('configuration.impuestos.toggle', $imp) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-sm px-3 py-1 rounded
                                            {{ $imp->estado ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}"
                                            title="{{ $imp->estado ? 'Desactivar' : 'Activar' }}">
                                            {{ $imp->estado ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                @else
                                    {{-- Mostrar alerta si es el último activo --}}
                                    <span class="text-xs text-red-500 p-1 rounded border border-red-300 bg-red-50"
                                          title="Debe haber al menos un impuesto activo">Último activo</span>
                                @endif

                                {{-- BOTÓN EDITAR --}}
                                @if($imp->estado)
                                    <button
                                        type="button"
                                        @click="$dispatch('open-modal', 'edit-impuesto-{{ $imp->id }}')"
                                        title="Editar impuesto"
                                        class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-100">
                                        <x-heroicon-s-pencil class="w-5 h-5" />
                                    </button>
                                @endif

                                {{-- ELIMINAR (Papelera) --}}
                                <button type="button" @click="$dispatch('open-modal', 'confirm-tax-deletion-{{ $imp->id }}')" 
                                    title="Eliminar Impuesto"
                                    class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-100">
                                    <x-heroicon-s-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-6 text-gray-500"> {{-- colspan actualizado a 8 --}}
                            No hay impuestos registrados.
                        </td>
                    </tr>
                @endforelse

            </x-data-table>
        </div>
    </div>

{{-- ========================================================================= --}}
{{-- MODAL DE CREACIÓN --}}
{{-- ========================================================================= --}}
<x-modal name="crear-impuesto" :show="false" maxWidth="md">
    <form action="{{ route('configuration.impuestos.store') }}" method="POST" class="p-6">
        @csrf

        {{-- Título --}}
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Crear nuevo impuesto') }}
        </h2>

        {{-- 1. Nombre --}}
        <div class="mt-4">
            <x-input-label for="nombre" value="Nombre del impuesto" />
            <x-text-input
                id="nombre"
                name="nombre"
                type="text"
                class="mt-1 block w-full"
                placeholder="Ej: IVA, ITBIS, Retención"
                required
            />
            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
        </div>

        <div class="mt-4 grid grid-cols-2 gap-4">
            {{-- 2. Tipo (Select) --}}
            <div>
                <x-input-label for="tipo" value="Tipo de cálculo" />
                <select id="tipo" name="tipo" class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="porcentaje" {{ old('tipo') === 'porcentaje' ? 'selected' : '' }}>Porcentaje</option>
                    <option value="fijo" {{ old('tipo') === 'fijo' ? 'selected' : '' }}>Fijo (Monto)</option>
                </select>
                <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
            </div>

            {{-- 3. Valor (Decimal) --}}
            <div>
                <x-input-label for="valor" value="Valor" />
                <x-text-input
                    id="valor"
                    name="valor"
                    type="number"
                    step="0.01" {{-- Permite dos decimales --}}
                    min="0"
                    class="mt-1 block w-full"
                    placeholder="0.00"
                    value="{{ old('valor') }}"
                    required
                />
                <x-input-error :messages="$errors->get('valor')" class="mt-2" />
            </div>
        </div>

        {{-- 4. Es Incluido (Checkbox) --}}
        <div class="mt-4 flex items-center">
            <input type="checkbox" id="es_incluido" name="es_incluido" value="1" 
                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                   {{ old('es_incluido') ? 'checked' : '' }}>
            <x-input-label for="es_incluido" value="¿El impuesto está incluido en el precio de venta?" class="ms-2" />
            <x-input-error :messages="$errors->get('es_incluido')" class="mt-2" />
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

{{-- LÓGICA DE ERROR PARA ABRIR MODAL --}}
@if ($errors->any())
    <script>
        window.addEventListener('load', () => {
            window.dispatchEvent(
                new CustomEvent('open-modal', {
                    detail: 'crear-impuesto'
                })
            )
        })
    </script>
@endif

{{-- ========================================================================= --}}
{{-- MODALES DE EDICIÓN Y ELIMINACIÓN --}}
{{-- ========================================================================= --}}
@foreach($impuesto as $imp)
<x-modal name="edit-impuesto-{{ $imp->id }}" :show="false" maxWidth="md">
    <form method="POST" action="{{ route('configuration.impuestos.update', $imp) }}" class="p-6">
        @csrf
        @method('PUT')

        {{-- TÍTULO --}}
        <h2 class="text-lg font-medium text-gray-900">
            Editar Impuesto
        </h2>

        {{-- DESCRIPCIÓN --}}
        <p class="mt-1 text-sm text-gray-600">
            Modifica los detalles del impuesto.
        </p>

        {{-- 1. NOMBRE --}}
        <div class="mt-4">
            <x-input-label for="nombre-{{ $imp->id }}" value="Nombre" />
            <x-text-input
                id="nombre-{{ $imp->id }}"
                name="nombre"
                type="text"
                class="mt-1 block w-full"
                value="{{ old('nombre', $imp->nombre) }}"
                required
                autofocus
            />
            {{-- Usar una clave de error específica para este modal si es necesario, 
                 o confiar en que Laravel sabrá el campo 'nombre' del formulario PUT --}}
            <x-input-error :messages="$errors->get('nombre')" class="mt-2" /> 
        </div>

        <div class="mt-4 grid grid-cols-2 gap-4">
            {{-- 2. Tipo (Select) --}}
            <div>
                <x-input-label for="tipo-{{ $imp->id }}" value="Tipo de cálculo" />
                <select id="tipo-{{ $imp->id }}" name="tipo" class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="porcentaje" {{ old('tipo', $imp->tipo) === 'porcentaje' ? 'selected' : '' }}>Porcentaje</option>
                    <option value="fijo" {{ old('tipo', $imp->tipo) === 'fijo' ? 'selected' : '' }}>Fijo (Monto)</option>
                </select>
                <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
            </div>

            {{-- 3. Valor (Decimal) --}}
            <div>
                <x-input-label for="valor-{{ $imp->id }}" value="Valor" />
                <x-text-input
                    id="valor-{{ $imp->id }}"
                    name="valor"
                    type="number"
                    step="0.01" {{-- Permite dos decimales --}}
                    min="0"
                    class="mt-1 block w-full"
                    placeholder="0.00"
                    value="{{ old('valor', $imp->valor) }}"
                    required
                />
                <x-input-error :messages="$errors->get('valor')" class="mt-2" />
            </div>
        </div>
        
        {{-- 4. Es Incluido (Checkbox) --}}
        <div class="mt-4 flex items-center">
            <input type="checkbox" id="es_incluido-{{ $imp->id }}" name="es_incluido" value="1" 
                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                   {{ old('es_incluido', $imp->es_incluido) ? 'checked' : '' }}>
            <x-input-label for="es_incluido-{{ $imp->id }}" value="¿El impuesto está incluido en el precio de venta?" class="ms-2" />
            <x-input-error :messages="$errors->get('es_incluido')" class="mt-2" />
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

@foreach($impuesto as $imp)
    <x-modal name="confirm-tax-deletion-{{ $imp->id }}" :show="false" maxWidth="md">
        <form method="post" action="{{ route('configuration.impuestos.destroy', $imp) }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                ¿Enviar impuesto a la papelera?
            </h2>

            <p class="mt-2 text-sm text-gray-600">
                El impuesto
                <span class="font-semibold text-gray-900">
                    {{ $imp->nombre }}
                </span>
                será movido a la
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
                    {{ __('Eliminar Impuesto') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
@endforeach
</x-config-layout>