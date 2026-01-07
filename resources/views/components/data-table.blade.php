@props(['items', 'headers', 'visibleColumns' => []])

<div class="flex flex-col">
    {{-- Contenedor con scroll horizontal para móviles (futuro) --}}
    <div class="overflow-x-auto border border-gray-200 rounded-lg custom-scrollbar">
        <table class="min-w-full divide-y divide-gray-200 shadow-sm">
            <thead class="bg-gray-50 hidden md:table-header-group">
                <tr>
                    @foreach($headers as $key => $label)
                        @if(in_array($key, $visibleColumns))
                            <th scope="col" class="px-6 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                {{ $label }}
                            </th>
                        @endif
                    @endforeach
                    <th scope="col" class="px-6 py-3 text-right text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if(method_exists($items, 'links'))
        <div class="mt-4 pagination">
            {{ $items->links() }}
        </div>
    @endif
</div>

<style>
    /* Estilo para la barra de scroll lateral en móviles */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>