@foreach($clients as $client)
        {{-- NUEVO MODAL: DETALLES COMPLETOS (RADICAL) --}}
        <x-modal name="view-client-{{ $client->id }}" maxWidth="2xl">
            <div class="p-8">
                {{-- Encabezado con Estilo --}}
                <div class="flex items-center justify-between mb-6 border-b pb-4">
                    <div class="flex items-center gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 leading-tight">{{ $client->display_name }}</h3>
                            <p class="text-sm font-medium text-gray-500 uppercase">{{ $client->type === 'individual' ? 'Persona Física' : 'Empresa / Jurídica' }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $client->estadoCliente->clase_fondo }} {{ $client->estadoCliente->clase_texto }}">
                        {{ $client->estadoCliente->nombre }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Información General --}}
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <h4 class="text-[11px] font-bold text-indigo-600 uppercase mb-3 flex items-center gap-1">
                            <x-heroicon-s-identification class="w-4 h-4"/> Identidad y Contacto
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-[10px] text-gray-400 uppercase block">{{ $client->tax_label }}:</label>
                                <p class="text-sm font-medium">{{ $client->tax_id ?? 'No registrado' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-400 uppercase block">Tipo:</label>
                                <p class="text-sm font-medium">{{ $client->type === 'company' ? 'Jurídico' : 'Físico' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-400 uppercase block">Correo</label>
                                <p class="text-sm font-medium">{{ $client->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-400 uppercase block">Teléfono</label>
                                <p class="text-sm font-medium">{{ $client->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Auditoría y Tiempos --}}
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <h4 class="text-[11px] font-bold text-amber-600 uppercase mb-3 flex items-center gap-1">
                            <x-heroicon-s-clock class="w-4 h-4"/> Tiempos y Registro
                        </h4>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white rounded shadow-sm"><x-heroicon-s-calendar class="w-4 h-4 text-gray-400"/></div>
                                <div>
                                    <label class="text-[10px] text-gray-400 uppercase block">Fecha de Registro</label>
                                    <p class="text-sm font-medium">{{ $client->created_at->format('d/m/Y h:i A') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white rounded shadow-sm"><x-heroicon-s-arrow-path class="w-4 h-4 text-gray-400"/></div>
                                <div>
                                    <label class="text-[10px] text-gray-400 uppercase block">Última Actualización</label>
                                    <p class="text-sm font-medium">{{ $client->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ubicación Completa --}}
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 md:col-span-2">
                        <h4 class="text-[11px] font-bold text-emerald-600 uppercase mb-3 flex items-center gap-1">
                            <x-heroicon-s-map-pin class="w-4 h-4"/> Dirección
                        </h4>
                        <p class="text-sm text-gray-700 leading-relaxed italic">
                            {{ $client->city }}, {{ $client->state->name }}.
                        </p>
                    </div>
                </div>

                {{-- Footer del Modal --}}
                <div class="mt-8 pt-6 border-t flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">Cerrar</x-secondary-button>
                    <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                        <x-heroicon-s-pencil class="w-4 h-4 mr-2" /> Editar Información
                    </a>
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

    