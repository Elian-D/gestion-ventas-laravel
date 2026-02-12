<x-data-table :items="$items" :headers="$allColumns" :visibleColumns="$visibleColumns" :bulkActions="false">
    @forelse($items as $sale)
        <tr class="hover:bg-gray-50 transition border-b border-gray-100">
            
            {{-- Fecha de Venta --}}
            @if(in_array('sale_date', $visibleColumns))
                <td class="px-6 py-4 text-xs text-gray-500">
                    <span class="block font-medium text-gray-700">{{ $sale->sale_date->format('d/m/Y') }}</span>
                    <span class="text-[10px]">{{ $sale->created_at->format('h:i A') }}</span>
                </td>
            @endif

            {{-- Folio / Número --}}
            @if(in_array('number', $visibleColumns))
                <td class="px-6 py-4 text-sm font-mono font-bold text-indigo-700">
                    {{ $sale->number }}
                </td>
            @endif

            {{-- Cliente --}}
            @if(in_array('client_id', $visibleColumns))
                <td class="px-6 py-4 text-sm">
                    <div class="font-medium text-gray-900">{{ $sale->client->name ?? 'N/A' }}</div>
                    <div class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $sale->client->tax_id ?? 'Consumidor Final' }}</div>
                </td>
            @endif

            {{-- Almacén --}}
            @if(in_array('warehouse_id', $visibleColumns))
                <td class="px-6 py-4 text-sm text-gray-600">
                    <div class="flex items-center">
                        <x-heroicon-s-building-storefront class="w-3.5 h-3.5 mr-1.5 text-gray-400" />
                        {{ $sale->warehouse->name ?? 'N/A' }}
                    </div>
                </td>
            @endif

            {{-- Tipo de Pago --}}
            @if(in_array('payment_type', $visibleColumns))
                <td class="px-6 py-4">
                    @php
                        $pStyles = \App\Models\Sales\Sale::getPaymentTypeStyles();
                        $pIcons  = \App\Models\Sales\Sale::getPaymentTypeIcons();
                        $pLabels = \App\Models\Sales\Sale::getPaymentTypes();
                        $currentStyle = $pStyles[$sale->payment_type] ?? 'bg-gray-100 text-gray-700';
                        $currentIcon  = $pIcons[$sale->payment_type] ?? 'heroicon-s-question-mark-circle';
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-tight ring-1 ring-inset {{ $currentStyle }}">
                        <x-dynamic-component :component="$currentIcon" class="w-3 h-3 mr-1" />
                        {{ $pLabels[$sale->payment_type] ?? $sale->payment_type }}
                    </span>
                </td>
            @endif
            
            {{-- Método de Pago Detallado --}}
            @if(in_array('tipo_pago_id', $visibleColumns))
                <td class="px-6 py-4 text-sm">
                    <div class="font-medium text-gray-900">{{ $sale->tipoPago->nombre ?? 'N/A' }}</div>
                </td>
            @endif

            {{-- Total Venta --}}
            @if(in_array('total_amount', $visibleColumns))
                <td class="px-6 py-4 text-sm text-right font-bold text-gray-900">
                    <span class="text-[10px] font-normal text-gray-400 mr-1">$</span>{{ number_format($sale->total_amount, 2) }}
                </td>
            @endif

            {{-- Estado --}}
            @if(in_array('status', $visibleColumns))
                <td class="px-6 py-4 text-center">
                    @php
                        $sStyles = \App\Models\Sales\Sale::getStatusStyles();
                        $sLabels = \App\Models\Sales\Sale::getStatuses();
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-sm ring-1 ring-inset {{ $sStyles[$sale->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $sLabels[$sale->status] ?? $sale->status }}
                    </span>
                </td>
            @endif

            {{-- Vendedor --}}
            @if(in_array('user_id', $visibleColumns))
                <td class="px-6 py-4 text-xs text-gray-500">
                    <div class="flex items-center">
                        {{ $sale->user->name ?? 'Sistema' }}
                    </div>
                </td>
            @endif

            {{-- Notas --}}
            @if(in_array('notes', $visibleColumns))
                <td class="px-6 py-4 text-xs text-gray-400 max-w-[150px] truncate italic" title="{{ $sale->notes }}">
                    {{ $sale->notes ?? 'Sin observaciones' }}
                </td>
            @endif

            {{-- Fecha Registro --}}
            @if(in_array('created_at', $visibleColumns))
                <td class="px-6 py-4 text-[11px] text-gray-400">
                    {{ $sale->created_at->format('d/m/Y h:i A') }}
                </td>
            @endif

            {{-- Última Actualización --}}
            @if(in_array('updated_at', $visibleColumns))
                <td class="px-6 py-4 text-[11px] text-gray-400">
                    {{ $sale->updated_at->format('d/m/Y h:i A') }}
                </td>
            @endif

            {{-- Acciones --}}
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    {{-- Ver Detalle --}}
                    <button @click="$dispatch('open-modal', 'view-sale-{{ $sale->id }}')" 
                            class="bg-white border border-gray-200 text-gray-500 hover:bg-indigo-600 hover:text-white p-2 rounded-lg transition-all shadow-sm"
                            title="Ver Detalle de Venta">
                        <x-heroicon-s-eye class="w-4 h-4" />
                    </button>

                    {{-- Imprimir Factura/Ticket --}}
                    <a href="{{ route('sales.print-invoice', $sale) }}" target="_blank" 
                    class="bg-white border border-gray-200 text-gray-500 hover:bg-gray-800 hover:text-white p-2 rounded-lg transition-all shadow-sm" 
                    title="Imprimir Comprobante">
                        <x-heroicon-s-printer class="w-4 h-4" />
                    </a>

                    {{-- Anular Venta (Solo si está completada) --}}
                    @can('cancel sales')
                        @if($sale->status === \App\Models\Sales\Sale::STATUS_COMPLETED)
                            <button @click="$dispatch('open-modal', 'confirm-cancel-sale-{{ $sale->id }}')" 
                                    class="bg-white border border-gray-200 text-red-600 hover:bg-red-50 p-2 rounded-lg transition-all shadow-sm"
                                    title="Anular Venta">
                                <x-heroicon-s-x-circle class="w-4 h-4" />
                            </button>
                        @endif
                    @endcan
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="100%" class="text-center py-16 text-gray-400 bg-gray-50/30">
                <x-heroicon-o-shopping-cart class="w-12 h-12 mx-auto text-gray-200 mb-2"/>
                <p class="text-sm">No se encontraron registros de ventas.</p>
            </td>
        </tr>
    @endforelse
</x-data-table>

@include('sales.partials.modals')