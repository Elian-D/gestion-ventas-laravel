{{-- Envolvemos todo el formulario en Alpine para manejar la lógica de estados --}}
<div class="p-4 sm:p-8 space-y-8 sm:space-y-10" 
     x-data="{ 
        requiresPin: {{ old('requires_pin', $posTerminal->requires_pin ?? true) ? 'true' : 'false' }},
        isEdit: {{ isset($posTerminal) ? 'true' : 'false' }}
     }">
     
    {{-- Sección 1: Identificación y Ubicación (Sin cambios significativos) --}}
    <section>
        <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-2">
            <div class="w-7 h-7 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-xs">1</div>
            <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Configuración General</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <x-input-label value="Nombre de la Terminal" />
                <x-text-input name="name" class="w-full mt-1" :value="old('name', $posTerminal->name ?? '')" placeholder="Ej: Caja Principal 01" required />
            </div>

            <div>
                <x-input-label value="Almacén de Despacho" />
                <select name="warehouse_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 text-sm" required>
                    <option value="">Seleccione almacén...</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $posTerminal->warehouse_id ?? '') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-[10px] text-gray-400 italic">El inventario se descontará de este almacén.</p>
            </div>

            <div class="flex items-center gap-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
                <x-input-label value="¿Es Terminal Móvil?" class="mb-0" />
                <input type="hidden" name="is_mobile" value="0">
                <input type="checkbox" name="is_mobile" value="1" {{ old('is_mobile', $posTerminal->is_mobile ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5 cursor-pointer">
            </div>
        </div>
    </section>

    {{-- Sección 2: Finanzas y Facturación (CORREGIDA) --}}
    <section>
        <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-2">
            <div class="w-7 h-7 bg-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-xs">2</div>
            <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Finanzas y Operación</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- ELIMINADO SELECT DE CUENTA CONTABLE - AGREGADO ALERT INFO --}}
            <div class="md:col-span-2 bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded-r-lg shadow-sm">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.040L3 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622l-0.382-3.016z"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-bold text-emerald-800 uppercase tracking-tight">Automatización Contable Activa</h4>
                        <p class="text-xs text-emerald-700 leading-relaxed mt-1">
                            El sistema generará y vinculará automáticamente una cuenta de <strong>Caja de Efectivo</strong> en el grupo contable <strong>1.1.01</strong>. No requiere configuración manual.
                            @if(isset($posTerminal->account))
                                <span class="block mt-1 font-mono font-bold">Cuenta actual: {{ $posTerminal->account->code }} - {{ $posTerminal->account->name }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            @if(general_config()?->esModoFiscal())
                <div class="md:col-span-2">
                    <x-input-label value="Tipo de Comprobante (NCF) por Defecto" />
                    <select name="default_ncf_type_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 text-sm">
                        <option value="">Seleccione tipo...</option>
                        @foreach($ncf_types as $ncf)
                            <option value="{{ $ncf['id'] }}" {{ old('default_ncf_type_id', $posTerminal->default_ncf_type_id ?? '') == $ncf['id'] ? 'selected' : '' }}>
                                {{ $ncf['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="md:col-span-2">
                <x-input-label value="Cliente por Defecto (Walk-in)" />
                <select name="default_client_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 text-sm">
                    <option value="" {{ is_null(old('default_client_id', $posTerminal->default_client_id ?? null)) ? 'selected' : '' }}>
                        Heredar de Ajustes POS (Actual: {{ $global_client_name }})
                    </option>
                    @foreach($clients as $client)
                        <option value="{{ $client['id'] }}" {{ old('default_client_id', $posTerminal->default_client_id ?? '') == $client['id'] ? 'selected' : '' }}>
                            {{ $client['name'] }} @if($client['tax_id'] != 'N/A') — {{ $client['tax_id'] }} @endif
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>

    {{-- Sección 3: Hardware (Sin cambios) --}}
    <section>
        <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-2">
            <div class="w-7 h-7 bg-amber-500 text-white rounded-full flex items-center justify-center font-bold text-xs">3</div>
            <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Hardware y Operación</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label value="Formato de Impresión" />
                <select name="printer_format" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 text-sm">
                    <option value="" {{ is_null(old('printer_format', $posTerminal->printer_format ?? null)) ? 'selected' : '' }}>
                        Heredar de Ajustes POS (Actual: {{ $global_printer_format }})
                    </option>
                    @foreach($printer_formats as $format)
                        <option value="{{ $format['id'] }}" {{ old('printer_format', $posTerminal->printer_format ?? '') == $format['id'] ? 'selected' : '' }}>
                            {{ $format['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
                <x-input-label value="Estado Operativo" class="mb-0" />
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $posTerminal->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 w-5 h-5 cursor-pointer">
            </div>
        </div>
    </section>

    {{-- Sección 4: Seguridad Operativa (CORREGIDA CON ALPINE) --}}
    <section>
        <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-2">
            <div class="w-7 h-7 bg-slate-800 text-white rounded-full flex items-center justify-center font-bold text-xs">4</div>
            <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Seguridad y Acceso</h3>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <input type="hidden" name="requires_pin" value="0">
                    <input type="checkbox" name="requires_pin" value="1" 
                        x-model="requiresPin"
                        class="mt-1 rounded border-gray-300 text-slate-800 focus:ring-slate-800 w-5 h-5 cursor-pointer flex-shrink-0">
                    <div class="flex-1">
                        <x-input-label value="¿Habilitar PIN de Seguridad?" class="mb-1 font-bold text-slate-700" />
                        <p class="text-xs text-gray-500 leading-relaxed italic">
                            Si se desactiva, cualquier usuario con permisos podrá abrir la terminal sin código adicional.
                        </p>
                    </div>
                </div>

                <div x-show="requiresPin" x-transition class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 flex gap-3 items-start">
                    <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <p class="text-[11px] text-indigo-700 leading-tight">
                        <strong>Entrada Rápida:</strong> Diseñado para pantallas táctiles. Use 4 dígitos numéricos.
                    </p>
                </div>
            </div>

            <div class="flex flex-col justify-center" x-show="requiresPin" x-transition>
                <div class="bg-slate-50 rounded-2xl p-6 border-2 border-slate-200" 
                     :class="requiresPin ? 'opacity-100' : 'opacity-50 pointer-events-none'">
                    
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-3 text-center">
                        Ingrese PIN de 4 dígitos
                    </label>
                    
                    <div class="flex justify-center">
                        <x-text-input 
                            name="access_pin" 
                            type="text"
                            maxlength="4"
                            pattern="[0-9]*"
                            inputmode="numeric"
                            class="w-full max-w-[240px] text-center text-4xl sm:text-5xl tracking-[0.5em] font-mono font-bold bg-white border-3 border-slate-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 rounded-xl py-4 sm:py-5 shadow-sm"
                            placeholder="••••"
                            ::required="requiresPin"
                            ::disabled="!requiresPin"
                            autocomplete="new-password"
                            @input="$el.value = $el.value.replace(/[^0-9]/g, '')"
                        />
                    </div>

                    @if(isset($posTerminal))
                        <p class="text-[10px] text-center text-blue-600 mt-3 font-medium italic">
                             Deje vacío para mantener el PIN actual
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>