{{-- resources/views/sales/ncf/types/partials/table.blade.php --}}
<x-data-table :items="$items" :headers="$allColumns" :visibleColumns="$visibleColumns" :bulkActions="false">
    @forelse($items as $type)
        <tr class="hover:bg-gray-50 transition border-b border-gray-100">
            
            {{-- Nombre del Comprobante --}}
            @if(in_array('name', $visibleColumns))
                <td class="px-6 py-4 text-sm font-bold text-gray-800">
                    {{ $type->name }}
                </td>
            @endif

            {{-- Prefijo (B o E) --}}
            @if(in_array('prefix', $visibleColumns))
                <td class="px-6 py-4 text-sm text-center">
                    <span class="font-mono bg-gray-100 px-2 py-1 rounded border text-gray-600">
                        {{ $type->prefix }}
                    </span>
                </td>
            @endif

            {{-- Código (01, 02, etc) --}}
            @if(in_array('code', $visibleColumns))
                <td class="px-6 py-4 text-sm font-mono text-indigo-600 font-bold">
                    {{ $type->code }}
                </td>
            @endif

            {{-- Es Electrónico? --}}
            @if(in_array('is_electronic', $visibleColumns))
                <td class="px-6 py-4 text-center">
                    @if($type->is_electronic)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200 uppercase">
                            <x-heroicon-s-cpu-chip class="w-3 h-3 mr-1" /> e-NCF
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200 uppercase">
                            Físico
                        </span>
                    @endif
                </td>
            @endif

            {{-- Requiere RNC --}}
            @if(in_array('requires_rnc', $visibleColumns))
                <td class="px-6 py-4 text-center">
                    @if($type->requires_rnc)
                        <span class="text-xs font-medium text-orange-600 flex items-center justify-center">
                            <x-heroicon-s-identification class="w-4 h-4 mr-1" /> Obligatorio
                        </span>
                    @else
                        <span class="text-xs text-gray-400">Opcional</span>
                    @endif
                </td>
            @endif

            {{-- Estado Activo/Inactivo --}}
            @if(in_array('is_active', $visibleColumns))
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border shadow-sm {{ $type->is_active ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                        {{ $type->is_active ? 'ACTIVO' : 'INACTIVO' }}
                    </span>
                </td>
            @endif

            {{-- Conteo de Secuencias (Usa withCount) --}}
            @if(in_array('sequences_count', $visibleColumns))
                <td class="px-6 py-4 text-center text-sm font-medium text-gray-600">
                    {{ $type->sequences_count ?? 0 }}
                </td>
            @endif

            {{-- Fecha de Creación --}}
            @if(in_array('created_at', $visibleColumns))
                <td class="px-6 py-4 text-sm text-gray-500">
                    <div class="font-medium">{{ $type->created_at->format('d/m/Y') }}</div>
                </td>
            @endif

            {{-- Acciones --}}
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <button @click="$dispatch('open-modal', 'edit-ncf-type-{{ $type->id }}')" 
                            class="p-2 text-gray-400 hover:text-indigo-600 transition"
                            title="Editar Configuración">
                        <x-heroicon-s-pencil-square class="w-5 h-5" />
                    </button>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="100%" class="text-center py-12 text-gray-400">
                <div class="flex flex-col items-center">
                    <x-heroicon-o-adjustments-horizontal class="w-12 h-12 mb-2 text-gray-200" />
                    <p>No se han configurado tipos de comprobantes.</p>
                </div>
            </td>
        </tr>
    @endforelse
</x-data-table>

@include('sales.ncf.types.partials.modals')