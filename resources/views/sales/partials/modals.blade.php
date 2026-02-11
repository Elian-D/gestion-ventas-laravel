@foreach($items as $sale)
    {{-- 1. MODAL: VISTA DE DETALLE DE VENTA --}}
    <x-modal name="view-sale-{{ $sale->id }}" maxWidth="2xl">
        <div class="overflow-hidden rounded-xl bg-white shadow-2xl">
            {{-- Header Dinámico según Estado --}}
            @php
                $statusStyles = \App\Models\Sales\Sale::getStatusStyles();
                $statusLabels = \App\Models\Sales\Sale::getStatuses();
                $paymentLabels = \App\Models\Sales\Sale::getPaymentTypes();
            @endphp

            <div class="bg-gray-50 px-8 py-6 border-b flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Detalle de Venta</h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs font-mono text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                            {{ $sale->number }}
                        </span>
                        <span class="text-gray-300 text-xs">•</span>
                        <span class="text-xs text-gray-500 italic">ID: {{ $sale->id }}</span>
                    </div>
                </div>

                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset shadow-sm {{ $statusStyles[$sale->status] ?? '' }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-2 bg-current {{ $sale->status === 'completed' ? 'animate-pulse' : '' }}"></span>
                    {{ strtoupper($statusLabels[$sale->status] ?? $sale->status) }}
                </span>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    {{-- Información del Cliente --}}
                    <div class="flex gap-3">
                        <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 shrink-0">
                            <x-heroicon-s-user class="w-5 h-5"/>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Cliente</span>
                            <p class="text-sm font-bold text-gray-800">{{ $sale->client->name ?? 'Consumidor Final' }}</p>
                            <p class="text-[10px] text-gray-500">{{ $sale->client->tax_id ?? '' }}</p>
                        </div>
                    </div>

                    {{-- Información de Pago --}}
                    <div class="flex gap-3">
                        <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 shrink-0">
                            <x-heroicon-s-credit-card class="w-5 h-5"/>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Método / Fecha</span>
                            <p class="text-sm font-semibold text-gray-700">{{ $paymentLabels[$sale->payment_type] ?? $sale->payment_type }}</p>
                            <p class="text-[10px] text-gray-500">{{ $sale->created_at->format('d/m/Y h:i A') }}</p>
                        </div>
                    </div>

                    {{-- Almacén y Vendedor --}}
                    <div class="flex gap-3">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 shrink-0">
                            <x-heroicon-s-building-storefront class="w-5 h-5"/>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Origen / Atendido por</span>
                            <p class="text-sm font-semibold text-gray-700">{{ $sale->warehouse->name ?? 'Principal' }}</p>
                            <p class="text-[10px] text-gray-500">{{ $sale->user->name ?? 'Sistema' }}</p>
                        </div>
                    </div>
                </div>

                {{-- TABLA DE ARTÍCULOS --}}
                <div class="border rounded-xl overflow-hidden mb-8">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-[10px] font-black uppercase text-gray-400">Producto</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase text-gray-400 text-center">Cant.</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase text-gray-400 text-right">Precio</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase text-gray-400 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            {{-- Cambia las líneas dentro del @foreach en la tabla de artículos --}}
                            @foreach($sale->items as $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $item->product->name ?? 'Producto Eliminado' }}</div>
                                        <div class="text-[10px] text-gray-400 font-mono">{{ $item->product->sku ?? '' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-gray-600">
                                        {{-- Usamos ?? 0 para evitar el error de null en number_format --}}
                                        {{ number_format($item->quantity ?? 0, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-600">
                                        {{-- Asegúrate que sea 'price' y no 'unit_price' --}}
                                        ${{ number_format($item->unit_price ?? 0, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900">
                                        {{-- Es mejor usar el campo subtotal de la DB --}}
                                        ${{ number_format($item->subtotal ?? 0, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50/80">
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-right font-bold text-gray-400 uppercase text-[10px]">Subtotal</td>
                                <td class="px-4 py-2 text-right font-mono text-gray-600">${{ number_format($sale->subtotal, 2) }}</td>
                            </tr>
                            @if($sale->tax_amount > 0)
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-right font-bold text-gray-400 uppercase text-[10px]">ITBIS (Impuestos)</td>
                                <td class="px-4 py-2 text-right font-mono text-gray-600">${{ number_format($sale->tax_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="border-t">
                                <td colspan="3" class="px-4 py-3 text-right font-black text-gray-900 uppercase text-[10px]">Total de la Operación</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="text-lg font-black text-indigo-700 font-mono">${{ number_format($sale->total_amount, 2) }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    {{-- NUEVO: DESGLOSE DE PAGO (SOLO CONTADO) --}}
                    @if($sale->payment_type === 'cash')
                        <div class="bg-gray-50/50 border-t px-4 py-4 grid grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Efectivo Recibido</span>
                                <span class="text-sm font-mono text-gray-600">${{ number_format($sale->cash_received ?? 0, 2) }}</span>
                            </div>
                            <div class="flex flex-col text-right">
                                <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Cambio Entregado</span>
                                <span class="text-sm font-mono font-bold text-emerald-600">${{ number_format($sale->cash_change ?? 0, 2) }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Notas --}}
                @if($sale->notes)
                    <div class="bg-amber-50 p-4 rounded-xl border border-dashed border-amber-200 mb-6">
                        <span class="text-[10px] font-bold text-amber-500 uppercase tracking-widest block mb-1">Observaciones</span>
                        <p class="text-sm text-amber-800 italic leading-relaxed">"{{ $sale->notes }}"</p>
                    </div>
                @endif

                <div class="flex justify-between items-center text-[11px] text-gray-400 uppercase font-bold tracking-tighter">
                    <span>Fecha Registro: {{ $sale->created_at->format('d/m/Y H:i') }}</span>
                    <span>Última mod: {{ $sale->updated_at->diffForHumans() }}</span>
                </div>
            </div>

            <div class="px-8 py-5 bg-gray-50 border-t flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cerrar</x-secondary-button>
                <a href="{{ route('sales.print-invoice', $sale) }}" target="_blank" 
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition shadow-sm">
                    <x-heroicon-s-printer class="w-3 h-3 mr-2"/> Re-imprimir Ticket
                </a>
            </div>
        </div>
    </x-modal>
    
    {{-- 2. MODAL: CONFIRMACIÓN DE ANULACIÓN ACTUALIZADO --}}
    <x-modal name="confirm-cancel-sale-{{ $sale->id }}" maxWidth="sm">
        <form action="{{ route('sales.cancel', $sale) }}" method="POST" class="p-6">
            @csrf
            @method('PATCH')
            
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <x-heroicon-s-exclamation-triangle class="w-10 h-10"/>
            </div>
            
            <div class="text-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">¿Anular Venta?</h3>
                <p class="text-xs text-gray-500 mt-1">
                    Venta: <strong>{{ $sale->number }}</strong>
                </p>
            </div>

            {{-- Nuevo: Campo de Motivo --}}
            <div class="mt-4 text-left">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Motivo de Anulación (DGII)</label>
                <select name="cancellation_reason" required class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="01 - ERRORES DE DIGITACION">01 - Errores de digitación</option>
                    <option value="02 - ERRORES DE IMPRESION">02 - Errores de impresión</option>
                    <option value="03 - PRODUCTO DEFECTUOSO">03 - Producto defectuoso</option>
                    <option value="04 - DEVOLUCION">04 - Devolución</option>
                    <option value="05 - OTROS">05 - Otros</option>
                </select>
            </div>

            <div class="mt-8 flex justify-center gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Volver</x-secondary-button>
                <button type="submit" class="px-6 py-2 bg-red-600 text-white text-xs font-bold uppercase rounded-lg hover:bg-red-700 transition-colors shadow-lg shadow-red-200">
                    Confirmar
                </button>
            </div>
        </form>
</x-modal>
@endforeach