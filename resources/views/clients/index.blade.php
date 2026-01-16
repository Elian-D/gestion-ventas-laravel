    @include('clients.partials.filter-sources')
<x-app-layout>

    <div class="w-full max-w-7xl mx-auto py-4 px-2 sm:px-3 lg:px-4">
        <div class="bg-white shadow-xl rounded-xl">
            
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

            <div class="p-6">
                
            <x-page-toolbar title="Gestión de Clientes">
                <x-slot name="actions">

                    <a href="{{ route('clients.eliminados') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100">
                        <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                        Papelera
                    </a>

                    <a href="{{ route('clients.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 rounded-md text-xs font-semibold text-white uppercase hover:bg-green-700">
                        <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                        Nuevo Cliente
                    </a>

                    <x-data-table.export-button 
                        :route="route('clients.export')" 
                        formId="clients-filters" 
                    />

                    <x-data-table.import-link 
                        :route="route('clients.import.view')" 
                        title="Importar clientes"
                    />

                </x-slot>
            </x-page-toolbar>


                {{-- FILTROS Y BARRA DE BÚSQUEDA --}}
                @include('clients.partials.filters')

                {{-- TABLA --}}
                <div id="clients-table" class="w-full overflow-hidden">
                    @include('clients.partials.table')
                </div>
            </div>
        </div>
    </div>

<x-data-table.bulk-confirmation-modal 
    formId="clients-filters" 
    route="{{ route('clients.bulk') }}" 
/>


</x-app-layout>