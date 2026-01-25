<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4">
            <form action="{{ route('clients.store') }}" method="POST"
                class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
            @csrf
            @if(isset($client)) @method('PUT') @endif

        <x-form-header
            title="Nuevo Cliente"
            subtitle="Complete todos los campos requeridos para la gestión comercial."
            :back-route="route('clients.index')" />


            <div class="p-8 space-y-8">
                {{-- Bloque 1: Datos de Identidad --}}
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center font-bold text-sm">1</div>
                        <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wider">Identidad Fiscal y Nombre</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
                        {{-- 1. Nombre del cliente --}}
                        <div class="md:col-span-4">
                            <x-input-label value="Nombre Completo / Razón Social" />
                            <x-text-input name="name" class="w-full mt-1" :value="old('name', $client->name ?? '')" required />
                        </div>

                        {{-- 2. Tipo de cliente --}}
                        <div class="md:col-span-2">
                            <x-input-label value="Tipo de Cliente" />
                            <select name="type" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500">
                                <option value="individual" {{ (old('type', $client->type ?? '') == 'individual') ? 'selected' : '' }}>Persona Física</option>
                                <option value="company" {{ (old('type', $client->type ?? '') == 'company') ? 'selected' : '' }}>Empresa / Corporativo</option>
                            </select>
                        </div>

                        {{-- 3. Tipo de identificador --}}
                        <div class="md:col-span-2">
                            <x-input-label value="Tipo de ID Fiscal" />
                            <select name="tax_identifier_type_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500">
                                @foreach($taxIdentifierTypes as $type)
                                    <option value="{{ $type->id }}" {{ (old('tax_identifier_type_id', $client->tax_identifier_type_id ?? '') == $type->id) ? 'selected' : '' }}>
                                        {{ $type->code }} – {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 4. Número Identificador --}}
                        <div class="md:col-span-2">
                            <x-input-label value="Número de ID Fiscal" />
                            <x-text-input name="tax_id" class="w-full mt-1" :value="old('tax_id', $client->tax_id ?? '')" />
                        </div>

                        {{-- 5. Estado --}}
                        <div class="md:col-span-2">
                            <x-input-label value="Estado Operativo" />
                            <select name="estado_cliente_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500">
                                @foreach($estados as $e)
                                    <option value="{{ $e->id }}" {{ (old('estado_cliente_id', $client->estado_cliente_id ?? '') == $e->id) ? 'selected' : '' }}>
                                        {{ $e->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>

                {{-- Bloque 2: Contacto y Localización --}}
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center font-bold text-sm">2</div>
                        <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wider">Contacto y Ubicación</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label value="Correo Electrónico" />
                            <x-text-input name="email" type="email" class="w-full mt-1" :value="old('email', $client->email ?? '')" />
                        </div>
                        <div>
                            <x-input-label value="Teléfono de Contacto" />
                            <x-text-input name="phone" class="w-full mt-1" :value="old('phone', $client->phone ?? '')" />
                        </div>
                        <div>
                            <x-input-label value="Provincia / Estado" />
                            <select name="state_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500">
                                @foreach($states as $s)
                                    <option value="{{ $s->id }}" {{ (old('state_id', $client->state_id ?? '') == $s->id) ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Municipio / Ciudad" />
                            <x-text-input name="city" class="w-full mt-1" :value="old('city', $client->city ?? '')" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label value="Dirección Exacta" />
                            <textarea name="address" rows="2" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 text-sm p-2.5" placeholder="Calle, número, edificio...">{{ old('address', $client->address ?? '') }}</textarea>
                        </div>
                    </div>
                </section>
            </div>

            <div class="p-6 bg-gray-50 flex justify-end gap-3 border-t">
                <a href="{{ route('clients.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">Cancelar</a>
                <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 shadow-lg px-8">
                    Registrar Cliente
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>