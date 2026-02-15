<div class="p-4 sm:p-8 space-y-8 sm:space-y-10">
    {{-- Sección 1: Identificación y Ubicación --}}
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

    {{-- Sección 2: Finanzas y Facturación --}}
    <section>
        <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-2">
            <div class="w-7 h-7 bg-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-xs">2</div>
            <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Finanzas y Operación</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label value="Cuenta de Caja (Contabilidad)" />
                <select name="cash_account_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 text-sm" required>
                    <option value="">Seleccione cuenta...</option>
                    @foreach($cash_accounts as $account)
                        <option value="{{ $account->id }}" {{ old('cash_account_id', $posTerminal->cash_account_id ?? '') == $account->id ? 'selected' : '' }}>
                            {{ $account->code }} - {{ $account->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Lógica Fiscal Flexible --}}
            @if(general_config()?->esModoFiscal())
                <div>
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
            @else
                <div class="flex items-center p-4 bg-amber-50 rounded-lg border border-amber-100">
                    <p class="text-[11px] text-amber-700 italic leading-tight">
                        <strong>Aviso:</strong> El modo fiscal está desactivado en la configuración general. No se requieren NCF para esta terminal.
                    </p>
                </div>
            @endif

            {{-- Cliente con Herencia Global --}}
            <div class="md:col-span-2">
                <x-input-label value="Cliente por Defecto (Walk-in)" />
                <select name="default_client_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 text-sm">
                    <option value="" {{ is_null(old('default_client_id', $posTerminal->default_client_id)) ? 'selected' : '' }}>
                        Heredar de Ajustes POS (Actual: {{ $global_client_name }})
                    </option>
                    @foreach($clients as $client)
                        <option value="{{ $client['id'] }}" {{ old('default_client_id', $posTerminal->default_client_id ?? '') == $client['id'] ? 'selected' : '' }}>
                            {{ $client['name'] }} @if($client['tax_id'] != 'N/A') — {{ $client['tax_id'] }} @endif
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-[10px] text-gray-400 italic">Si se deja en "Heredar", el sistema usará el cliente configurado globalmente.</p>
            </div>
        </div>
    </section>

    {{-- Sección 3: Hardware y Estado --}}
    <section>
        <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-2">
            <div class="w-7 h-7 bg-amber-500 text-white rounded-full flex items-center justify-center font-bold text-xs">3</div>
            <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Hardware y Operación</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label value="Formato de Impresión" />
                <select name="printer_format" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 text-sm">
                    <option value="" {{ is_null(old('printer_format', $posTerminal->printer_format)) ? 'selected' : '' }}>
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
                <span class="text-[10px] text-gray-400 italic">Determina si la terminal está disponible para ventas.</span>
            </div>
        </div>
    </section>

    {{-- Sección 4: Seguridad Operativa (PIN) --}}
    <section>
        <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-2">
            <div class="w-7 h-7 bg-slate-800 text-white rounded-full flex items-center justify-center font-bold text-xs">4</div>
            <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Seguridad y Acceso</h3>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Columna Izquierda: Información y Toggle --}}
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <input type="hidden" name="requires_pin" value="0">
                    <input type="checkbox" name="requires_pin" value="1" 
                        {{ old('requires_pin', $posTerminal->requires_pin ?? true) ? 'checked' : '' }} 
                        class="mt-1 rounded border-gray-300 text-slate-800 focus:ring-slate-800 w-5 h-5 cursor-pointer flex-shrink-0">
                    <div class="flex-1">
                        <x-input-label value="PIN de Seguridad (4 dígitos)" class="mb-1 font-bold text-slate-700" />
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Este código será requerido para abrir sesión y confirmar cierres de caja en esta terminal.
                        </p>
                    </div>
                </div>

                @if(isset($posTerminal))
                    <div class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span>PIN ya configurado (Dejar vacío para mantener el actual)</span>
                    </div>
                @endif

                <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 flex gap-3 items-start">
                    <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <p class="text-[11px] text-indigo-700 leading-tight">
                        <strong>Seguridad de Terminal:</strong> A diferencia de la contraseña de usuario, este PIN está diseñado para una entrada rápida en pantallas táctiles o teclados numéricos.
                    </p>
                </div>
            </div>

            {{-- Columna Derecha: Input de PIN --}}
            <div class="flex flex-col justify-center">
                <div class="bg-slate-50 rounded-2xl p-6 border-2 border-slate-200">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-3">
                        Ingrese el PIN
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
                            :value="old('access_pin')"
                            autocomplete="off"
                            x-data
                            @input="$el.value = $el.value.replace(/[^0-9]/g, '')"
                        />
                    </div>
                    
                    @error('access_pin')
                        <div class="mt-3 text-sm text-red-600 font-medium bg-red-50 p-3 rounded-lg border border-red-200 flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </section>
</div>