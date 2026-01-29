<x-data-table 
    :items="$items" 
    :headers="['name' => 'Equipo', 'pos' => 'Punto de Venta', 'deleted_at' => 'Eliminado']"
    :visibleColumns="['name', 'pos', 'deleted_at']">

    @forelse($items as $equipment)
        <tr class="hover:bg-gray-50 transition duration-150 group">
            {{-- Nombre y Código del Equipo --}}
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-600 mr-3">
                        <x-heroicon-s-cube class="w-4 h-4"/>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-gray-900">{{ $equipment->name }}</div>
                        <div class="text-xs font-mono text-gray-500">{{ $equipment->code }}</div>
                    </div>
                </div>
            </td>

            {{-- Punto de Venta asociado --}}
            <td class="px-6 py-4 text-sm text-gray-600">
                <div class="flex flex-col">
                    <span class="font-medium">{{ $equipment->pointOfSale->name ?? 'Sin asignar' }}</span>
                    <span class="text-xs text-gray-400">{{ $equipment->pointOfSale->city ?? '' }}</span>
                </div>
            </td>

            {{-- Fecha de eliminación --}}
            <td class="px-6 py-4 text-sm text-gray-500 italic">
                {{ $equipment->deleted_at->diffForHumans() }}
            </td>

            {{-- Acciones --}}
            <td class="px-6 py-4 text-right">
                <div class="flex justify-end gap-2">
                    {{-- Restaurar --}}
                    <form action="{{ route('clients.equipment.restore', $equipment->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" title="Restaurar Equipo"
                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                            <x-heroicon-s-arrow-path class="w-5 h-5" />
                        </button>
                    </form>

                    {{-- Borrado Definitivo --}}
                    <button type="button" 
                            @click="$dispatch('open-modal', 'confirm-deletion-{{ $equipment->id }}')"
                            title="Eliminar permanentemente"
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <x-heroicon-s-trash class="w-5 h-5" />
                    </button>
                </div>
            </td>
        </tr>

    @empty
        <tr>
            <td colspan="4" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center">
                    <x-heroicon-s-cube class="w-12 h-12 text-gray-200 mb-3"/>
                    <p class="text-gray-500 font-medium">No hay equipos en la papelera.</p>
                </div>
            </td>
        </tr>
    @endforelse
</x-data-table>

@foreach($items as $equipment)

    <x-ui.confirm-deletion-modal 
        :id="$equipment->id"
        :title="'¿Eliminar Permanentemente?'"
        :itemName="$equipment->name"
        :route="route('clients.borrarDefinitivo', $equipment->id)"
        :description="'Estás a punto de borrar definitivamente el equipo <strong>' . $equipment->name . '</strong>.'"
    >
        <strong>Aviso Crítico:</strong> Esta operación borrará todos los datos asociados y no se puede deshacer.
    </x-ui.confirm-deletion-modal>
@endforeach