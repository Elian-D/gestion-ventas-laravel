@foreach($sessions->where('status', \App\Models\Sales\Pos\PosSession::STATUS_OPEN) as $session)
<x-modal name="close-session-{{ $session->id }}" maxWidth="lg">
    <x-form-header 
        title="Arqueo y Cierre de Caja" 
        subtitle="Sesión #{{ $session->id }} - {{ $session->terminal->name }}" />

    <form action="{{ route('sales.pos.sessions.close', $session) }}" 
        method="POST" 
        class="p-6"
        x-data="{ 
            {{-- Inyectamos el valor que viene del Service a través del controlador o una variable --}}
            expected: {{ $session->calculateExpected() }}, 
            real: '',
            get difference() { 
                return this.real === '' ? 0 : (parseFloat(this.real) - this.expected).toFixed(2);
            }
        }">
        @csrf
        @method('PATCH')
        
        <div class="bg-gray-50 rounded-2xl p-5 mb-6 border border-gray-100 space-y-3 shadow-sm">
            {{-- Cambiamos las etiquetas para que sean claras --}}
            <div class="flex justify-between text-xs font-medium text-gray-500">
                <span>(+) Fondo Inicial:</span>
                <span class="font-mono font-bold text-gray-700">${{ number_format($session->opening_balance, 2) }}</span>
            </div>
            {{-- Usamos las relaciones directas del modelo --}}
            <div class="flex justify-between text-xs font-medium text-blue-600">
                <span>(+) Entradas Manuales:</span>
                <span class="font-mono font-bold">${{ number_format($session->cashMovements()->in()->sum('amount'), 2) }}</span>
            </div>
            <div class="flex justify-between text-xs font-medium text-red-500">
                <span>(-) Salidas/Gastos:</span>
                <span class="font-mono font-bold">(${{ number_format($session->cashMovements()->out()->sum('amount'), 2) }})</span>
            </div>
            <div class="pt-3 border-t border-dashed border-gray-200 flex justify-between items-center">
                <span class="text-sm font-black text-indigo-900 uppercase">Monto Esperado:</span>
                {{-- Reflejamos lo que Alpine usará --}}
                <span class="font-mono text-xl font-black text-indigo-600" x-text="'$' + expected.toLocaleString()"></span>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <x-input-label for="closing_balance_{{ $session->id }}" value="Monto Real en Caja (Arqueo Físico)" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-400 font-bold">$</span>
                    </div>
                    <x-text-input 
                        id="closing_balance_{{ $session->id }}" 
                        name="closing_balance" 
                        type="number" 
                        step="0.01" 
                        x-model="real"
                        class="pl-8 block w-full text-xl font-black text-gray-800 bg-white focus:ring-indigo-500" 
                        placeholder="0.00" 
                        required 
                    />
                </div>
            </div>

            <template x-if="real !== ''">
                <div :class="difference == 0 ? 'bg-green-50 border-green-200 text-green-700' : (difference < 0 ? 'bg-red-50 border-red-200 text-red-700' : 'bg-amber-50 border-amber-200 text-amber-700')"
                     class="p-4 rounded-xl border flex justify-between items-center animate-in fade-in zoom-in duration-300">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-70" 
                              x-text="difference == 0 ? 'Balance Perfecto' : (difference > 0 ? 'Sobrante en Caja' : 'Faltante en Caja')"></span>
                        <span class="text-lg font-black font-mono" x-text="(difference > 0 ? '+' : '') + '$' + difference"></span>
                    </div>
                    <template x-if="difference == 0">
                        <x-heroicon-s-check-circle class="w-8 h-8 text-green-500" />
                    </template>
                    <template x-if="difference != 0">
                        <x-heroicon-s-exclamation-triangle class="w-8 h-8 opacity-50" />
                    </template>
                </div>
            </template>

            <div>
                <x-input-label for="notes_{{ $session->id }}" value="Observaciones del Cierre" />
                <textarea name="notes" id="notes_{{ $session->id }}" rows="2" 
                    class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" 
                    placeholder="Escriba aquí si hubo alguna novedad con el efectivo..."></textarea>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-3">
            <x-primary-button class="w-full justify-center py-3 bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-100">
                Finalizar Sesión y Registrar Arqueo
            </x-primary-button>
            <x-secondary-button x-on:click="$dispatch('close')" class="w-full justify-center">
                Seguir Operando
            </x-secondary-button>
        </div>
    </form>
</x-modal>
@endforeach