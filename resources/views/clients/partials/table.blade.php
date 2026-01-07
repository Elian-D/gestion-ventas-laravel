<x-data-table :items="$clients" :headers="['ID', 'Cliente', 'Ubicación', 'Estado del cliente', 'Estado Operativo']">
    @forelse($clients as $client)
        <tr class="flex flex-col md:table-row border-b border-gray-200 hover:bg-gray-50 transition p-4 md:p-0">

            <td class="px-6 py-2 md:py-4 hidden sm:table-cell">
                <div class="text-sm text-gray-600">
                    {{ $client->id }}
                </div>
            </td>
            
            <td class="px-6 py-2 md:py-4 md:table-cell">
                <div class="flex flex-col">
                    <span class="font-bold text-gray-900">{{ $client->display_name }}</span>
                    <span class="text-xs text-gray-500">{{ $client->tax_id ?? 'Sin RNC' }}</span>
                </div>
            </td>

            <td class="px-6 py-2 md:py-4 hidden sm:table-cell">
                <div class="text-sm text-gray-600">
                    {{ $client->city }}, {{ $client->state->name }}
                </div>
            </td>

            <td class="px-6 py-2 md:py-4 md:table-cell">
                <div class="flex gap-2 items-center">
                    <span class="px-2 py-1 text-[10px] md:text-xs rounded font-bold {{ $client->estadoCliente->clase_fondo }} {{ $client->estadoCliente->clase_texto }}">
                        {{ $client->estadoCliente->nombre }}
                    </span>
                    
                    <div class="md:hidden">
                        @if($client->active)
                            <span class="px-2 py-1 text-[10px] bg-green-100 text-green-700 rounded-full font-semibold">Activo</span>
                        @else
                            <span class="px-2 py-1 text-[10px] bg-red-100 text-red-700 rounded-full font-semibold">Inactivo</span>
                        @endif
                    </div>
                </div>
            </td>
            
            <td class="hidden md:table-cell px-6 py-4">
                @if($client->active)
                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full font-semibold">Activo</span>
                @else
                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full font-semibold">Inactivo</span>
                @endif
            </td>

            <td class="px-6 py-4 md:table-cell">
                <div class="flex items-center justify-start md:justify-end gap-2">
                    @include('clients.partials.actions')
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center py-10 text-gray-500 italic">
                No hay clientes registrados
            </td>
        </tr>
    @endforelse
</x-data-table>

@include('clients.modals')