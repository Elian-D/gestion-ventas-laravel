<script>
    window.filterSources = {
        ncf_types: JSON.parse('{!! addslashes(json_encode($ncf_types)) !!}'),
        statuses: JSON.parse('{!! addslashes(json_encode($statuses)) !!}'),
    };
</script>

<x-app-layout>
    <div class="w-full max-w-7xl mx-auto py-4 px-2 sm:px-3 lg:px-4">
        <div class="bg-white shadow-xl rounded-xl">
            <x-ui.toasts />

            <div class="p-6">
                <x-page-toolbar title="Configuración de Secuencias NCF">
                    <x-slot name="actions">
                        @can('manage ncf sequences')
                            <button x-data="" x-on:click="$dispatch('open-modal', 'create-ncf-sequence')"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                                <x-heroicon-s-plus-circle class="w-4 h-4 mr-2" />
                                Nuevo Lote NCF
                            </button>
                        @endcan
                    </x-slot>
                </x-page-toolbar>

                {{-- Filtros del Pipeline --}}
                @include('sales.ncf.sequences.partials.filters')

                {{-- Contenedor de Tabla AJAX --}}
                <div id="ncf-sequences-table" class="w-full overflow-hidden mt-4">
                    @include('sales.ncf.sequences.partials.table')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>