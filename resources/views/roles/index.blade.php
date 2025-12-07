<x-app-layout>
    
    {{-- Eliminamos el x-slot:header para integrar el título en el contenido --}}

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                {{-- 1. MENSAJE DE SESIÓN --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                        <span class="block sm:inline font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                
                {{-- TÍTULO MINIMALISTA --}}
                <h2 class="text-xl font-medium text-gray-700 mb-6 border-b pb-3">{{ __('Gestión de Roles') }}</h2>

                {{-- 2. BARRA DE HERRAMIENTAS (Búsqueda y Creación) --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                    
                    {{-- Formulario de Búsqueda Estilizado --}}
                    <form action="{{ route('roles.index') }}" method="GET" class="w-full md:w-1/3">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Buscar roles..." 
                                   value="{{ $search ?? '' }}"
                                   class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm pl-10 pr-4 py-2">
                            <x-heroicon-s-magnifying-glass class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                        </div>
                    </form>

                    {{-- Botón Estilizado para Crear Rol --}}
                    <a href="{{ route('roles.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                        <x-heroicon-s-plus class="w-5 h-5 mr-2 -ml-1" />
                        {{ __('Crear Nuevo Rol') }}
                    </a>
                </div>

                {{-- 3. TABLA ESTILIZADA --}}
                <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-7/12">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-4/12">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($roles as $role)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $role->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $role->name }}</td>
                                    
                                    {{-- ACCIONES COMPACTAS: Solo Íconos --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-2">
                                        
                                        {{-- Botón Editar (Ícono) --}}
                                        <a href="{{ route('roles.edit', $role) }}" 
                                           title="Editar Rol"
                                           class="text-indigo-600 hover:text-indigo-900 p-1 rounded-md hover:bg-indigo-100 transition duration-150">
                                            <x-heroicon-s-pencil class="w-5 h-5" />
                                        </a>
                                        
                                        {{-- Botón Asignar Permisos (Ícono) --}}
                                        <a href="{{ route('roles.permissions.edit', $role) }}"
                                           title="Asignar Permisos"
                                           class="text-green-600 hover:text-green-900 p-1 rounded-md hover:bg-green-100 transition duration-150">
                                            <x-heroicon-s-shield-check class="w-5 h-5" />
                                        </a>

                                        {{-- Botón Eliminar con Formulario (Ícono) --}}
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="button"
                                                @click="$dispatch('open-modal', 'confirm-role-deletion-{{ $role->id }}')"
                                                title="Eliminar Rol"
                                                class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-100 transition duration-150">
                                                <x-heroicon-s-trash class="w-5 h-5" />
                                            </button>
                                        </form>
                                        
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500 text-sm">No se encontraron roles.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- 4. PAGINACIÓN --}}
                <div class="mt-6">
                    {{ $roles->links() }}
                </div>

            </div>
        </div>
    </div>
    {{-- Al final de roles/index.blade.php --}}

@foreach($roles as $role)
    <x-modal name="confirm-role-deletion-{{ $role->id }}" :show="$errors->roleDeletion->isNotEmpty()" maxWidth="md">
        <form method="post" action="{{ route('roles.destroy', $role) }}" class="p-6">
            @csrf
            @method('delete')

            {{-- Título y Mensaje de Advertencia --}}
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('¿Estás seguro de que quieres eliminar este rol?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Esta acción es irreversible. Estás a punto de eliminar el rol: ') }}
                <span class="font-bold text-red-600">{{ $role->name }}</span>.
                {{ __('Asegúrate de que no hay usuarios asignados a este rol antes de proceder.') }}
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
                    {{ __('Eliminar Rol') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
@endforeach
</x-app-layout>