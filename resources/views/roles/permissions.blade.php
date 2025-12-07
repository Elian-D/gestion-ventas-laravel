<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Asignar Permisos al rol: ') . $role->name }}
        </h1>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6">
                        <span class="block sm:inline font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('roles.permissions.update', $role) }}" method="POST">
                    @csrf

                    @foreach($groupedPermissions as $groupName => $perms)
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ $groupName }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($perms as $permission)
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->name }}"
                                               id="perm_{{ $permission->id }}"
                                               {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <label for="perm_{{ $permission->id }}" class="text-gray-700 text-sm">{{ $permission->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    <div class="flex items-center space-x-3 text-sm font-medium">
                        
                        {{-- Botón Seleccionar Todos --}}
                        <button type="button" 
                                @click="$nextTick(() => { document.querySelectorAll('input[type=checkbox]').forEach(el => el.checked = true) })"
                                class="text-indigo-600 hover:text-indigo-800 transition duration-150 flex items-center">
                            <x-heroicon-s-check-circle class="w-4 h-4 mr-1" />
                            Seleccionar Todos
                        </button>

                        <span class="text-gray-300">|</span>

                        {{-- Botón Quitar Todos --}}
                        <button type="button" 
                                @click="$nextTick(() => { document.querySelectorAll('input[type=checkbox]').forEach(el => el.checked = false) })"
                                class="text-red-600 hover:text-red-800 transition duration-150 flex items-center">
                            <x-heroicon-s-x-circle class="w-4 h-4 mr-1" />
                            Quitar Todos
                        </button>
                    </div>
                    <div class="mt-6">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            Guardar Permisos
                        </button>
                        <a href="{{ route('roles.index') }}" class="ml-3 text-sm text-gray-600 hover:underline">Cancelar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
