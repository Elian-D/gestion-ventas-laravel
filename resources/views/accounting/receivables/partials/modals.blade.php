@foreach($items as $item)
    <x-modal name="view-receivable-{{ $item->id }}" maxWidth="2xl">
        <div class="overflow-hidden rounded-xl bg-white shadow-2xl">
            {{-- Header --}}
            <div class="bg-gray-50 px-8 py-6 border-b flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Detalle de Cuenta por Cobrar</h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs font-mono text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                            {{ $item->document_number }}
                        </span>
                        <span class="text-gray-300 text-xs">•</span>
                        <span class="text-xs text-gray-500 italic">ID: {{ $item->id }}</span>
                    </div>
                </div>

                {{-- Estado Dinámico desde el Modelo --}}
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset shadow-sm {{ \App\Models\Accounting\Receivable::getStatusStyles()[$item->status] }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-2 bg-current animate-pulse"></span>
                    {{ strtoupper($item->status_label) }}
                </span>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- Columna Izquierda --}}
                    <div class="space-y-6">
                        <div class="flex gap-3">
                            <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 shrink-0">
                                <x-heroicon-s-user class="w-5 h-5"/>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Cliente</span>
                                <p class="text-sm font-bold text-gray-800">{{ $item->client->name }}</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 shrink-0">
                                <x-heroicon-s-calendar class="w-5 h-5"/>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Fecha Emisión</span>
                                <p class="text-sm font-semibold text-gray-700">{{ $item->emission_date->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="w-10 h-10 {{ $item->is_overdue ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600' }} rounded-lg flex items-center justify-center shrink-0">
                                <x-heroicon-s-clock class="w-5 h-5"/>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Vencimiento</span>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-semibold text-gray-700">{{ $item->due_date->format('d/m/Y') }}</p>
                                    @if($item->is_overdue)
                                        <span class="px-2 py-0.5 bg-red-600 text-[9px] text-white font-black uppercase rounded tracking-tighter">Vencido</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Columna Derecha: Finanzas y Contabilidad --}}
                    <div class="space-y-6">
                        {{-- Card de Estado de Cuenta --}}
                        <div class="p-5 {{ $item->status === 'paid' ? 'bg-emerald-900' : 'bg-indigo-900' }} rounded-2xl shadow-lg relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                            
                            <span class="text-[10px] font-bold text-white/50 uppercase tracking-widest block mb-4">Resumen de Cobro</span>
                            
                            <div class="flex justify-between items-center mb-2 text-white/80">
                                <span class="text-xs">Monto Original:</span>
                                <span class="text-sm font-bold">${{ number_format($item->total_amount, 2) }}</span>
                            </div>

                            @if($item->current_balance < $item->total_amount && $item->current_balance > 0)
                                <div class="flex justify-between items-center mb-2 text-emerald-400">
                                    <span class="text-xs">Total Abonado:</span>
                                    <span class="text-sm font-bold">-${{ number_format($item->total_amount - $item->current_balance, 2) }}</span>
                                </div>
                            @endif
                            
                            <hr class="border-white/10 my-3">
                            
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-white font-bold uppercase">Saldo Actual:</span>
                                <span class="text-xl font-black text-white tracking-tight">${{ number_format($item->current_balance, 2) }}</span>
                            </div>
                        </div>

                        {{-- Validación de Cuenta Contable --}}
                        <div class="flex gap-3 px-1">
                            <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400 shrink-0">
                                <x-heroicon-s-book-open class="w-5 h-5"/>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Cuenta Contable Destino</span>
                                @if($item->client->accounting_account_id)
                                    {{-- Cuenta propia del cliente --}}
                                    <p class="text-xs font-mono text-indigo-600">
                                        {{ $item->client->accountingAccount->code }} <br>
                                        <span class="font-sans font-bold text-gray-800">{{ $item->client->accountingAccount->name }}</span>
                                        <span class="block text-[9px] text-indigo-400 font-sans italic underline decoration-indigo-200">Cuenta específica del cliente</span>
                                    </p>
                                @else
                                    {{-- Cuenta general de la CxC --}}
                                    <p class="text-xs font-mono text-gray-600">
                                        {{ $item->accountingAccount->code ?? '1.1.02' }} <br>
                                        <span class="font-sans font-bold text-gray-800">{{ $item->accountingAccount->name ?? 'Cuenta Por Cobrar General' }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Concepto --}}
                    <div class="col-span-1 md:col-span-2">
                        <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-200">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Concepto o Descripción</span>
                            <p class="text-sm text-gray-700 leading-relaxed italic">"{{ $item->description }}"</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between text-[11px] text-gray-400 uppercase font-bold tracking-tighter">
                    <span>Creado: {{ $item->created_at->format('d/m/Y H:i') }}</span>
                    <span>Último cambio: {{ $item->updated_at->diffForHumans() }}</span>
                </div>
            </div>

            <div class="px-8 py-5 bg-gray-50 border-t flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cerrar</x-secondary-button>
                @can('edit receivables')
                <a href="{{ route('accounting.receivables.edit', $item) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-sm">
                    <x-heroicon-s-pencil class="w-3 h-3 mr-2"/> Editar Cuenta
                </a>
                @endcan
            </div>
        </div>
    </x-modal>

    <x-modal name="add-payment-{{ $item->id }}" maxWidth="lg">
        <form action="{{ route('accounting.receivables.payment', $item) }}" method="POST" class="p-6">
            @csrf
            <h2 class="text-lg font-bold text-gray-900 mb-1">Registrar Abono</h2>
            <p class="text-sm text-gray-500 mb-6 font-mono">{{ $item->document_number }}</p>

            {{-- Resumen de saldo --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-6 flex justify-between items-center">
                <div>
                    <span class="text-[10px] uppercase font-black text-indigo-400 block">Saldo Pendiente</span>
                    <span class="text-2xl font-black text-indigo-700">${{ number_format($item->current_balance, 2) }}</span>
                </div>
                <x-heroicon-s-banknotes class="w-8 h-8 text-indigo-300"/>
            </div>

            <div class="space-y-4">
                {{-- Monto a Pagar --}}
                <div>
                    <x-input-label for="payment_amount" value="Monto del Abono" />
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <x-text-input 
                            name="payment_amount" 
                            type="number" 
                            step="0.01" 
                            max="{{ $item->current_balance }}"
                            class="block w-full pl-7" 
                            placeholder="0.00"
                            required 
                        />
                    </div>
                </div>

                {{-- Fecha de Pago (Solo lectura) --}}
                <div>
                    <x-input-label for="payment_date" value="Fecha de Recepción" />
                    <x-text-input 
                        type="date" 
                        name="payment_date" 
                        class="block w-full mt-1 bg-gray-50 text-gray-500 cursor-not-allowed" 
                        value="{{ date('Y-m-d') }}" 
                        readonly 
                        tabindex="-1"
                    />
                    <p class="mt-1 text-[10px] text-gray-400 italic">La fecha de registro es automática.</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                    Confirmar Pago
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- C. MODAL: ANULACIÓN (Reemplaza el confirm nativo) --}}
    <x-modal name="confirm-cancel-{{ $item->id }}" maxWidth="sm">
        <form action="{{ route('accounting.receivables.cancel', $item) }}" method="POST" class="p-6 text-center">
            @csrf
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <x-heroicon-s-exclamation-triangle class="w-10 h-10"/>
            </div>
            <h3 class="text-lg font-bold text-gray-900">¿Anular Cuenta por Cobrar?</h3>
            <p class="text-sm text-gray-500 mt-2">
                Esta acción cancelará la factura <strong>{{ $item->document_number }}</strong> y la moverá a la papelera. Esta operación es irreversible si ya existen cierres contables.
            </p>

            <div class="mt-8 flex justify-center gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">No, mantener</x-secondary-button>
                <button type="submit" class="px-6 py-2 bg-red-600 text-white text-xs font-bold uppercase rounded-lg hover:bg-red-700">
                    Sí, Anular
                </button>
            </div>
        </form>
    </x-modal>
@endforeach