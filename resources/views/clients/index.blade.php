    @include('clients.partials.filter-sources')
<x-app-layout>

    <div class="w-full max-w-7xl mx-auto py-4 px-2 sm:px-3 lg:px-4">
        <div class="bg-white shadow-xl rounded-xl">
            
            <x-ui.toasts />

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