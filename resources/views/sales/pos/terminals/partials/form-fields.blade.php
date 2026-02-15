<div class="p-8 space-y-10">
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
</div>