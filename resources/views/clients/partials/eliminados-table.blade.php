<x-data-table 
    :items="$items" 
    :headers="['name' => 'Cliente', 'tax_id' => 'Identificación', 'deleted_at' => 'Eliminado']"
    :visibleColumns="['name', 'tax_id', 'deleted_at']">

    @forelse($items as $client)
        <tr class="hover:bg-gray-50 transition duration-150 group">
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-600 mr-3">
                        <x-heroicon-s-user class="w-4 h-4"/>
                    </div>
                    <div class="text-sm font-bold text-gray-900">{{ $client->name }}</div>
                </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">
                <span class="bg-gray-100 px-2 py-1 rounded text-xs font-mono">{{ $client->tax_id }}</span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-500 italic">
                {{ $client->deleted_at->diffForHumans() }}
            </td>
            <td class="px-6 py-4 text-right space-x-2">
                <div class="flex justify-end gap-2">
                    {{-- Restaurar --}}
                    <form action="{{ route('clients.restore', $client->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" title="Restaurar Cliente"
                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                            <x-heroicon-s-arrow-path class="w-5 h-5" />
                        </button>
                    </form>

                    {{-- Borrado Definitivo --}}
                    <button type="button" 
                            @click="$dispatch('open-modal', 'confirm-delete-{{ $client->id }}')"
                            title="Eliminar permanentemente"
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <x-heroicon-s-trash class="w-5 h-5" />
                    </button>
                </div>
            </td>
        </tr>

        {{-- Modal de Confirmación Definitiva --}}
        <x-modal name="confirm-delete-{{ $client->id }}" maxWidth="md">
            <form action="{{ route('clients.borrarDefinitivo', $client->id) }}" method="POST" class="p-3">
                @csrf @method('DELETE')
                <div class="text-center p-6">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <x-heroicon-s-exclamation-triangle class="h-6 w-6 text-red-600" />
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">¿Eliminar permanentemente?</h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Esta acción borrará a <strong>{{ $client->name }}</strong> de la base de datos para siempre. 
                        No podrás recuperarlo.
                    </p>
                </div>
                <div class="flex justify-center gap-3 p-3">
                    <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                    <x-danger-button>Sí, eliminar definitivamente</x-danger-button>
                </div>
            </form>
        </x-modal>
    @empty
        <tr>
            <td colspan="4" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center">
                    <x-heroicon-s-trash class="w-12 h-12 text-gray-200 mb-3"/>
                    <p class="text-gray-500 font-medium">La papelera está vacía.</p>
                </div>
            </td>
        </tr>
    @endforelse
</x-data-table>