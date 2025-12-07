<x-app-layout>
    
    <x-slot name="header">
        <h1 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Gestión de Roles') }}
        </h1>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                {{-- 1. MENSAJE DE SESIÓN --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                        <span class="block sm:inline font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                
                {{-- TÍTULO AÑADIDO (Roles) --}}
                <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('Roles') }}</h2>
                
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8/12">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($roles as $role)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $role->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $role->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-3">
                                        
                                        {{-- Botón Editar --}}
                                        <a href="{{ route('roles.edit', $role) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 transition flex items-center">
                                            <x-heroicon-s-pencil class="w-4 h-4" />
                                        </a>
                                        
                                        {{-- Botón Eliminar con Formulario --}}
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('¿Seguro que quieres eliminar el rol: {{ $role->name }}?')"
                                                    class="text-red-600 hover:text-red-900 transition flex items-center">
                                                <x-heroicon-s-trash class="w-4 h-4" />
                                            </button>
                                        </form>

                                        <a href="{{ route('roles.permissions.edit', $role) }}"
                                        class="text-green-600 hover:text-green-900 transition flex items-center">
                                            <x-heroicon-s-shield-check class="w-4 h-4 mr-1" />
                                            Permisos
                                        </a>

                                        
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
</x-app-layout>