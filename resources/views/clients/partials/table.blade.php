<x-data-table :items="$clients" :headers="$allColumns" :visibleColumns="$visibleColumns">
    @forelse($clients as $client)
        <tr class="hover:bg-gray-50 transition border-b border-gray-100">
            @if(in_array('id', $visibleColumns))
                <td class="px-6 py-4 text-sm text-gray-500 font-mono">#{{ $client->id }}</td>
            @endif

            @if(in_array('cliente', $visibleColumns))
                <td class="px-6 py-4">
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-900">{{ $client->display_name }}</span>
                        <span class="text-[10px] text-gray-400 font-mono">{{ $client->tax_id ?? 'Sin ID' }}</span>
                    </div>
                </td>
            @endif

            @if(in_array('ubicacion', $visibleColumns))
                <td class="px-6 py-4 text-sm text-gray-600 italic">
                    {{ $client->city }}, {{ $client->state->name }}
                </td>
            @endif

            @if(in_array('estado_cliente', $visibleColumns))
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-[10px] rounded font-bold {{ $client->estadoCliente->clase_fondo }} {{ $client->estadoCliente->clase_texto }}">
                        {{ $client->estadoCliente->nombre }}
                    </span>
                </td>
            @endif

            @if(in_array('estado_operativo', $visibleColumns))
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-[10px] {{ $client->active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full font-bold">
                        {{ $client->active ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
            @endif

            @if(in_array('created_at', $visibleColumns))
                <td class="px-6 py-4 text-xs text-gray-400">
                    {{ $client->created_at->format('d/m/Y h:i A') }}
                </td>
            @endif

            @if(in_array('updated_at', $visibleColumns))
                <td class="px-6 py-4 text-xs text-gray-400">
                    {{ $client->updated_at->diffForHumans() }}
                </td>
            @endif

            <td class="px-6 py-4">
                @include('clients.partials.actions')
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