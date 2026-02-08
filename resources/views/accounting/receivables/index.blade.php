@push('scripts')
    <script>
        window.filterSources = {
            clients: JSON.parse('{!! addslashes(json_encode($clients->pluck("name", "id"))) !!}'),
            statuses: JSON.parse('{!! addslashes(json_encode($statuses)) !!}'),
        };
    </script>
@endpush

<x-app-layout>
    <div class="w-full max-w-7xl mx-auto py-4 px-2 sm:px-3 lg:px-4">
        <div class="bg-white shadow-xl rounded-xl">
            <x-ui.toasts />

            <div class="p-6">
                <x-page-toolbar title="Cuentas por Cobrar (Facturación)"></x-page-toolbar>

                {{-- Filtros del Pipeline --}}
                @include('accounting.receivables.partials.filters')

                {{-- Tabla AJAX --}}
                <div id="receivables-table" class="w-full overflow-hidden">
                    @include('accounting.receivables.partials.table')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>