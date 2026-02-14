{{-- resources/views/sales/pos/sessions/partials/modal-open.blade.php --}}
<x-modal name="open-session-modal" maxWidthendif="md">
    <x-form-header 
        title="Apertura de Caja" 
        subtitle="Inicie una nueva sesión para comenzar a facturar." />

    <form action="{{ route('sales.pos.sessions.store') }}" 
          method="POST" 
          class="p-6"
          x-data="{ 
            terminalId: '',
            balance: 0,
            get isReady() { return this.terminalId !== '' && this.balance >= 0 }
          }">
        @csrf
        
        <div class="space-y-4">
            {{-- 1. Selección de Terminal --}}
            <div>
                <x-input-label for="terminal_id" value="Seleccionar Terminal / Punto de Venta" />
                <select name="terminal_id" id="terminal_id" x-model="terminalId"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                    <option value="">Seleccione una terminal disponible...</option>
                    @foreach($available_terminals as $terminal)
                        <option value="{{ $terminal->id }}">
                            {{ $terminal->name }} ({{ $terminal->warehouse->name ?? 'Sin Almacén' }})
                        </option>
                    @endforeach
                </select>
                @if($available_terminals->isEmpty())
                    <p class="mt-2 text-xs text-red-500 font-medium italic">
                        * No hay terminales disponibles o todas están ocupadas.
                    </p>
                @endif
            </div>

            {{-- 2. Fondo de Caja Inicial --}}
            <div>
                <x-input-label for="opening_balance" value="Monto Inicial (Fondo de Caja)" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <x-text-input 
                        id="opening_balance" 
                        name="opening_balance" 
                        type="number" 
                        step="0.01" 
                        x-model="balance"
                        class="pl-7 block w-full" 
                        placeholder="0.00" 
                        required 
                    />
                </div>
                <p class="mt-1 text-[10px] text-gray-500 italic">
                    Dinero en efectivo disponible para dar cambio (menudo).
                </p>
            </div>

            {{-- 3. Notas --}}
            <div>
                <x-input-label for="notes" value="Observaciones de Apertura" />
                <textarea name="notes" id="notes" rows="2" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm" 
                    placeholder="Opcional: Estado de la terminal, billetes recibidos, etc."></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
            <x-primary-button 
                class="bg-emerald-600 hover:bg-emerald-700"
                ::disabled="!isReady">
                Confirmar Apertura
            </x-primary-button>
        </div>
    </form>
</x-modal>