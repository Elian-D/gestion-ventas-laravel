{{-- resources/views/sales/pos/sessions/partials/modal-close.blade.php --}}
@foreach($sessions->where('status', \App\Models\Sales\Pos\PosSession::STATUS_OPEN) as $session)
<x-modal name="close-session-{{ $session->id }}" maxWidth="lg">
    <x-form-header 
        title="Arqueo y Cierre de Caja" 
        subtitle="Verifique los montos antes de finalizar la sesión." />

    @php
        // Estos valores vendrán de relaciones o métodos en tu modelo PosSession
        $opening = $session->opening_balance;
        $cashSales = $session->cash_sales ?? 0; // Sumatoria de ventas tipo 'cash'
        $cashOut = $session->cash_movements_out ?? 0; // Egresos manuales
        $expected = ($opening + $cashSales) - $cashOut;
    @endphp

    <form action="{{ route('sales.pos.sessions.close', $session) }}" 
        method="POST" 
        class="p-6"
        x-data="{ 
            expected: {{ $expected }},
            real: '',
            get difference() { 
                return this.real === '' ? 0 : (parseFloat(this.real) - this.expected).toFixed(2);
            }
        }">
        @csrf
        @method('PATCH') {{-- ... resto del contenido del formulario ... --}}

        
        <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200 space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">(+) Fondo Inicial:</span>
                <span class="font-mono font-bold">${{ number_format($opening, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm text-green-600">
                <span>(+) Ventas en Efectivo:</span>
                <span class="font-mono font-bold">${{ number_format($cashSales, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm text-red-600">
                <span>(-) Salidas/Gastos de Caja:</span>
                <span class="font-mono font-bold">(${{ number_format($cashOut, 2) }})</span>
            </div>
            <div class="pt-2 border-t border-dashed border-gray-300 flex justify-between text-base font-black text-indigo-800">
                <span>(=) Monto Esperado:</span>
                <span class="font-mono text-lg">${{ number_format($expected, 2) }}</span>
            </div>
        </div>

        

        <div class="space-y-4">
            {{-- Monto Real Contado --}}
            <div>
                <x-input-label for="closing_balance" value="Monto Real en Caja (Arqueo Físico)" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <x-text-input 
                        id="closing_balance" 
                        name="closing_balance" 
                        type="number" 
                        step="0.01" 
                        x-model="real"
                        class="pl-7 block w-full text-lg font-bold" 
                        placeholder="Ingrese el total contado" 
                        required 
                    />
                </div>
            </div>

            {{-- Visualización de Diferencia Dinámica --}}
            <template x-if="real !== ''">
                <div :class="difference == 0 ? 'bg-green-50 border-green-200 text-green-700' : 'bg-amber-50 border-amber-200 text-amber-700'"
                     class="p-3 rounded-lg border flex justify-between items-center transition-all">
                    <span class="text-xs font-bold uppercase" x-text="difference == 0 ? 'Caja Cuadrada' : (difference > 0 ? 'Sobrante' : 'Faltante')"></span>
                    <span class="font-mono font-bold text-lg" x-text="'$' + difference"></span>
                </div>
            </template>

            <div>
                <x-input-label for="notes" value="Notas de Cierre" />
                <textarea name="notes" id="notes" rows="2" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" 
                    placeholder="Explique cualquier diferencia o novedad..."></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                Finalizar Sesión y Cerrar Caja
            </x-primary-button>
        </div>
    </form>
</x-modal>
@endforeach