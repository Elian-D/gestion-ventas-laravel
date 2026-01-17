<x-data-table :items="$clients" :headers="$allColumns" :visibleColumns="$visibleColumns" :bulkActions="$bulkActions" >
    @forelse($clients as $client)
        <tr class="hover:bg-gray-50 transition border-b border-gray-100">

<tr class="hover:bg-gray-50 transition border-b border-gray-100">
            @if($bulkActions)
                <td class="px-4 py-4 text-center">
                    <input type="checkbox" value="{{ $client->id }}" class="row-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                </td>
            @endif

            @if(in_array('id', $visibleColumns))
                <td class="px-6 py-4 text-sm text-gray-500">#{{ $client->id }}</td>
            @endif

            @if(in_array('name', $visibleColumns))
                <td class="px-6 py-4 text-sm ">
                    @if($client->commercial_name)
                        <span class="text-gray-900">{{ $client->commercial_name }}</span>
                    @else
                        <span class="text-gray-900">{{ $client->name }}</span>
                    @endif
                </td>
            @endif

            @if(in_array('tax_identifier_types', $visibleColumns))
                <td class="px-6 py-4 text-sm text-gray-700">
                    {{ $client->tax_label ?? '—' }}
                </td>
            @endif

            @if(in_array('tax_id', $visibleColumns))
                <td class="px-6 py-4 text-sm text-gray-700">
                    {{ $client->tax_id ?? '—' }}
                </td>
            @endif



            @if(in_array('type', $visibleColumns))
                <td class="px-6 py-4 text-xs text-gray-700">
                    {{ $client->type === 'company' ? 'Empresa' : 'Individual' }}
                </td>
            @endif

            @if(in_array('email', $visibleColumns))
                <td class="px-6 py-4 text-sm text-blue-600">
                    {{ $client->email ?? '—' }}
                </td>
            @endif

            @if(in_array('phone', $visibleColumns))
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $client->phone ?? '—' }}
                </td>
            @endif

            @if(in_array('city', $visibleColumns))
                <td class="px-6 py-4 text-sm text-gray-700">{{ $client->city }}</td>
            @endif

            @if(in_array('state', $visibleColumns))
                <td class="px-6 py-4 text-sm text-gray-600">{{ $client->state->name ?? '—' }}</td>
            @endif

            @if(in_array('estado_cliente', $visibleColumns))
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded font-bold {{ $client->estadoCliente->clase_fondo }} {{ $client->estadoCliente->clase_texto }}">
                        {{ $client->estadoCliente->nombre }}
                    </span>
                </td>
            @endif

            @if(in_array('estado_operativo', $visibleColumns))
                <td class="px-6 py-4 text-center">
                    <span class="px-2 py-1 text-[10px] {{ $client->active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full font-bold uppercase">
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
                <div class="flex items-center gap-3 mt-2 md:mt-0">
                    {{-- BOTÓN RADICAL: VER TODO (MODAL) --}}

                    {{-- Toggle Activo --}}
                    <form action="{{ route('clients.toggle', $client) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-xs px-2 py-1 rounded border {{ $client->active ? 'bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100' : 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' }}">
                            {{ $client->active ? 'Deshabilitar' : 'Habilitar' }}
                        </button>
                    </form>

                    <button @click="$dispatch('open-modal', 'view-client-{{ $client->id }}')" 
                            class="bg-gray-100 text-gray-600 hover:bg-indigo-600 hover:text-white p-2 rounded-full transition-all shadow-sm"
                            title="Ver detalles completos">
                        <x-heroicon-s-eye class="w-5 h-5" />
                    </button>

                    <a href="{{ route('clients.edit', $client) }}" class="text-indigo-600 hover:text-indigo-900 p-2 rounded-full hover:bg-indigo-50">
                        <x-heroicon-s-pencil class="w-5 h-5" />
                    </a>

                    <button @click="$dispatch('open-modal', 'confirm-deletion-{{ $client->id }}')" class="text-red-600 hover:text-red-900 p-2 rounded-full hover:bg-red-50">
                        <x-heroicon-s-trash class="w-5 h-5" />
                    </button>
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