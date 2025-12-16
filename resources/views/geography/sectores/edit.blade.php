<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <h2 class="text-xl font-medium text-gray-700 mb-6 border-b pb-3">
                    {{ __('Editar Sector: ') }} <span class="text-indigo-600 font-semibold">{{ $sector->nombre }}</span>
                </h2>

                <form action="{{ route('geography.sectores.update', $sector) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nombre del Sector --}}
                    <div class="mb-6">
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Sector:</label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               value="{{ old('nombre', $sector->nombre) }}"
                               placeholder="Ej: Centro, Barrio Norte"
                               class="w-full border-gray-300 rounded-md shadow-sm text-base py-2.5 px-4
                                      focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50
                                      @error('nombre') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror">
                        @error('nombre') 
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    {{-- Seleccionar Municipio --}}
                    <div class="mb-6">
                        <label for="municipio_id" class="block text-sm font-medium text-gray-700 mb-2">Municipio:</label>
                        <select name="municipio_id" id="municipio_id"
                                class="w-full border-gray-300 rounded-md shadow-sm text-base py-2.5 px-4
                                       focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50
                                       @error('municipio_id') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror">
                            <option value="">Seleccione un municipio</option>
                            @foreach($municipios as $municipio)
                                <option value="{{ $municipio->id }}" {{ old('municipio_id', $sector->municipio_id) == $municipio->id ? 'selected' : '' }}>
                                    {{ $municipio->nombre }} ({{ $municipio->provincia->nombre ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('municipio_id') 
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end space-x-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('geography.sectores.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150">
                            <x-heroicon-s-check class="w-5 h-5 mr-2 -ml-1" />
                            Actualizar Sector
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
