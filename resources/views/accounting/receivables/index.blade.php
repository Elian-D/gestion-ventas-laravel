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
                <x-page-toolbar title="Cuentas por Cobrar (Facturación)">
                    <x-slot name="actions">
                        @can('create receivables')
                            <a href="{{ route('accounting.receivables.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-md text-xs font-semibold text-white uppercase hover:bg-indigo-700 transition">
                                <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                                Nueva CxC Manual
                            </a>
                        @endcan

                        @can('cancel receivables')
                        <a href="{{ route('accounting.receivables.eliminados') }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100">
                            <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                            Papelera
                        </a>
                        @endcan
                    </x-slot>
                </x-page-toolbar>

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