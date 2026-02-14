{{-- resources/views/sales/pos/sessions/index.blade.php --}}

<script>
    window.filterSources = {
        terminals: JSON.parse('{!! addslashes(json_encode($terminals->pluck('name', 'id'))) !!}'),
        users: JSON.parse('{!! addslashes(json_encode($users->pluck('name', 'id'))) !!}'),
        statuses: JSON.parse('{!! addslashes(json_encode($statuses)) !!}'),
    };
</script>

<x-app-layout>
    <div class="w-full max-w-7xl mx-auto py-4 px-2 sm:px-3 lg:px-4">
        <div class="bg-white shadow-xl rounded-xl">
            <x-ui.toasts />

            <div class="p-6">
                <x-page-toolbar title="Historial de Sesiones POS">
                    <x-slot name="actions">
                        <div class="flex flex-wrap gap-2">
                            {{-- Botón para abrir modal de apertura de caja --}}
                            @can('pos sessions manage')
                                <button x-data="" x-on:click="$dispatch('open-modal', 'open-session-modal')"
                                    class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition">
                                    <x-heroicon-s-lock-open class="w-4 h-4 mr-2" />
                                    Nueva Sesión (Apertura)
                                </button>
                            @endcan
                        </div>
                    </x-slot>
                </x-page-toolbar>

                {{-- Filtros (Pipeline) --}}
                @include('sales.pos.sessions.partials.filters')

                {{-- Contenedor de Tabla AJAX --}}
                <div id="pos-sessions-table" class="w-full overflow-hidden mt-4">
                    @include('sales.pos.sessions.partials.table')
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DE APERTURA --}}
    @include('sales.pos.sessions.partials.modal-open')
    @include('sales.pos.sessions.partials.modal-close')
</x-app-layout>