{{-- resources/views/sales/ncf/types/index.blade.php --}}
<x-app-layout>
    <div class="w-full max-w-7xl mx-auto py-4 px-2 sm:px-3 lg:px-4">
        <div class="bg-white shadow-xl rounded-xl">
            <x-ui.toasts />

            <div class="p-6">
                <x-page-toolbar title="Tipos de Comprobantes (NCF / e-NCF)">
                    <x-slot name="actions">
                        @can('manage ncf types')
                            <button x-data="" x-on:click="$dispatch('open-modal', 'create-ncf-type')"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                                <x-heroicon-s-plus-circle class="w-4 h-4 mr-2" />
                                Nuevo Tipo de NCF
                            </button>
                        @endcan
                    </x-slot>
                </x-page-toolbar>

                {{-- FILTROS SIMPLIFICADOS (Solo Columnas y PerPage) --}}
                @include('sales.ncf.types.partials.filters')
                
                {{-- Contenedor de Tabla AJAX --}}
                <div id="ncf-types-table" class="w-full overflow-hidden mt-6">
                    @include('sales.ncf.types.partials.table')
                </div>
            </div>
        </div>
    </div>

</x-app-layout>