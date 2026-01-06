<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg p-6">
            
            {{-- SECCIÓN DE ALERTAS (TOASTS) --}}
            <div class="fixed top-4 right-4 z-50 flex flex-col gap-4 w-full max-w-sm px-4 md:px-0">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        class="overflow-hidden rounded-lg shadow-2xl border border-emerald-600">
                        <div class="bg-emerald-600 px-4 py-2 flex justify-between items-center">
                            <span class="text-white font-bold text-sm">Operación Exitosa</span>
                            <button @click="show = false" class="text-white"><x-heroicon-s-x-mark class="w-4 h-4" /></button>
                        </div>
                        <div class="bg-emerald-500 px-4 py-3">
                            <p class="text-white text-sm leading-relaxed">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <h2 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-3">Gestión de Clientes</h2>

            {{-- Toolbar --}}
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
                <form method="GET" class="w-full md:w-2/3 flex gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nombre, RNC o email..."
                           class="w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        <x-heroicon-s-magnifying-glass class="w-5 h-5" />
                    </button>
                    
                    <select name="estado" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm">
                        <option value="">Todos</option>
                        <option value="activo" {{ $estadoFiltro === 'activo' ? 'selected' : '' }}>Habilitados</option>
                        <option value="inactivo" {{ $estadoFiltro === 'inactivo' ? 'selected' : '' }}>Deshabilitados</option>
                    </select>
                </form>

                <div class="flex gap-2">
                    <a href="{{ route('clients.eliminados') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-100">
                        <x-heroicon-s-trash class="w-5 h-5 mr-2" /> Papelera
                    </a>
                    <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                        <x-heroicon-s-plus class="w-5 h-5 mr-2" /> Nuevo
                    </a>
                </div>
            </div>

            {{-- TABLA --}}
            <x-data-table :items="$clients" :headers="['Cliente', 'Ubicación', 'Estado']">
                @forelse($clients as $client)
                    <tr class="block md:table-row hover:bg-gray-50 transition duration-150 p-4 border-b border-gray-200 md:border-b-0">
                        
                        <td class="block md:table-cell px-6 py-4 w-full md:w-4/12">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900 text-base md:text-sm">{{ $client->display_name }}</span>
                                <span class="text-xs text-gray-500">{{ $client->tax_id ?? 'Sin RNC' }}</span>
                            </div>
                        </td>

                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-600 w-3/12">
                            {{ $client->city }}, {{ $client->state->name }}
                        </td>

                        <td class="block md:table-cell px-6 py-4 w-full md:w-2/12">
                            <span class="px-2 py-1 text-xs rounded font-bold {{ $client->estadoCliente->clase_fondo }} {{ $client->estadoCliente->clase_texto }}">
                                {{ $client->estadoCliente->nombre }}
                            </span>
                        </td>

                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap text-sm font-medium w-full md:w-3/12">
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
                    <tr><td colspan="4" class="text-center py-10 text-gray-500">No hay clientes.</td></tr>
                @endforelse
            </x-data-table>
        </div>
    </div>

    @include('clients.modals')
</x-app-layout>