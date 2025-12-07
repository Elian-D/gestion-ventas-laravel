<x-app-layout>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Tarjeta/Contenedor del Formulario --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">

                {{-- TÍTULO MINIMALISTA AÑADIDO AQUÍ --}}
                <h2 class="text-xl font-medium text-gray-700 mb-6 border-b pb-3">{{ __('Crear Nuevo Rol') }}</h2>
                {{-- Se redujo a text-xl y font-medium, con una línea sutil de separación. --}}
                
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    
                    {{-- Campo de Formulario --}}
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Rol:</label>
                        
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               placeholder="Ej: Administrador, Vendedor, Logística"
                               class="w-full border-gray-300 rounded-md shadow-sm text-base py-2.5 px-4 
                                      focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50
                                      @error('name') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror">

                        {{-- Mensaje de Error Estilizado --}}
                        @error('name') 
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex justify-end space-x-4 pt-4 border-t border-gray-100">
                        
                        {{-- Botón Cancelar (Regresar a la lista) --}}
                        <a href="{{ route('roles.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            Cancelar
                        </a>
                        
                        {{-- Botón de Guardar --}}
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            <x-heroicon-s-check class="w-5 h-5 mr-2 -ml-1" />
                            Guardar Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>