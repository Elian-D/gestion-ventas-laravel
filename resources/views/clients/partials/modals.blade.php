@foreach($clients as $client)
        {{-- MODAL: DETALLES COMPLETOS --}}
        <x-modal name="view-client-{{ $client->id }}" maxWidth="2xl">
            <div class="overflow-hidden rounded-xl">
                {{-- Header con degradado suave y Estado --}}
                <div class="bg-gradient-to-r from-gray-50 to-white px-8 py-6 border-b relative">
                    <div class="flex justify-between items-start">
                        <div class="flex gap-4 items-center">
                            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-700 shadow-sm">
                                @if($client->type === 'company')
                                    <x-heroicon-s-building-office class="w-7 h-7"/>
                                @else
                                    <x-heroicon-s-user class="w-7 h-7"/>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 leading-tight">{{ $client->name }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs font-semibold px-2 py-0.5 bg-gray-200 text-gray-700 rounded uppercase tracking-wider">
                                        {{ $client->type === 'individual' ? 'Físico' : 'Jurídico' }}
                                    </span>
                                    @if($client->commercial_name)
                                        <span class="text-gray-400 text-xs">•</span>
                                        <span class="text-sm text-gray-500 font-medium italic">{{ $client->commercial_name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold ring-1 ring-inset shadow-sm {{ $client->estadoCliente->clase_fondo }} {{ $client->estadoCliente->clase_texto }} ring-black/5">
                            <span class="w-1.5 h-1.5 rounded-full mr-2 bg-current animate-pulse"></span>
                            {{ $client->estadoCliente->nombre }}
                        </span>
                    </div>
                </div>

                <div class="p-8 bg-white">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Columna Izquierda: Identidad y Contacto --}}
                        <div class="space-y-6">
                            <section>
                                <h4 class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <x-heroicon-s-identification class="w-4 h-4"/> Documentación y Fiscal
                                </h4>
                                <div class="grid grid-cols-1 gap-y-3">
                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                        <span class="text-[10px] text-gray-400 uppercase font-bold block">Tipo de Identificador</span>
                                        <p class="text-sm font-semibold text-gray-700">{{ $client->taxIdentifierType->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="bg-indigo-50/30 p-3 rounded-lg border border-indigo-100/50">
                                        <span class="text-[10px] text-indigo-400 uppercase font-bold block">{{ $client->tax_label }}</span>
                                        <p class="text-sm font-bold text-indigo-900 tracking-wider">{{ $client->tax_id ?? 'No registrado' }}</p>
                                    </div>
                                </div>
                            </section>

                            <section>
                                <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <x-heroicon-s-phone class="w-4 h-4"/> Medios de Contacto
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <x-heroicon-s-envelope class="w-5 h-5 text-gray-300"/>
                                        <div>
                                            <span class="text-[10px] text-gray-400 uppercase block">Email</span>
                                            <a href="mailto:{{ $client->email }}" class="text-sm font-medium text-indigo-600 hover:underline">{{ $client->email ?? 'N/A' }}</a>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-heroicon-s-device-phone-mobile class="w-5 h-5 text-gray-300"/>
                                        <div>
                                            <span class="text-[10px] text-gray-400 uppercase block">Teléfono</span>
                                            <p class="text-sm font-medium text-gray-700">{{ $client->phone ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- Columna Derecha: Ubicación y Auditoría --}}
                        <div class="space-y-6">
                            <section>
                                <h4 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <x-heroicon-s-map-pin class="w-4 h-4"/> Ubicación Geográfica
                                </h4>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 space-y-3">
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase block">Provincia / Estado</span>
                                        <p class="text-sm font-medium">{{ $client->state->name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase block">Ciudad / Municipio</span>
                                        <p class="text-sm font-medium">{{ $client->city }}</p>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase block">Dirección</span>
                                        <p class="text-sm text-gray-600 leading-relaxed">{{ $client->address }}</p>
                                    </div>
                                </div>
                            </section>

                            <section>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <x-heroicon-s-clock class="w-4 h-4"/> Registro de Sistema
                                </h4>
                                <div class="flex flex-col gap-2">
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-gray-400">Creado:</span>
                                        <span class="font-medium text-gray-600">{{ $client->created_at->format('d/m/Y h:i A') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-gray-400">Modificado:</span>
                                        <span class="font-medium text-gray-600">{{ $client->updated_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                    {{-- Footer con acciones --}}
                    <div class="mt-10 pt-6 border-t flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-[10px] text-gray-300 uppercase tracking-tighter">ID Interno: {{ $client->id }}</div>
                        <div class="flex gap-3 w-full sm:w-auto">
                            <x-secondary-button x-on:click="$dispatch('close')" class="flex-1 sm:flex-none justify-center">
                                Cerrar
                            </x-secondary-button>
                            <a href="{{ route('clients.edit', $client) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition duration-150 shadow-md shadow-indigo-100">
                                <x-heroicon-s-pencil class="w-4 h-4 mr-2" /> Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </x-modal>

        {{-- MODAL ELIMINAR (SIN CAMBIOS SEGÚN TU INSTRUCCIÓN) --}}
        <x-modal name="confirm-deletion-{{ $client->id }}" maxWidth="md">
            <form method="post" action="{{ route('clients.destroy', $client) }}" class="p-6">
                @csrf @method('delete')
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Enviar Cliente a la papelera?
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    El cliente
                    <span class="font-semibold text-gray-900">
                        {{ $client->nombre }}
                    </span>
                    será movida a la
                    <span class="font-semibold text-yellow-600">papelera</span>.
                </p>
                <p class="mt-1 text-sm text-gray-500">
                    Esta acción se puede revertir desde la papelera.
                </p>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancelar') }}
                    </x-secondary-button>
                    <x-danger-button class="ms-3">
                        <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                        {{ __('Eliminar Cliente') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach

    