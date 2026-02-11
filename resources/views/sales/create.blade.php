<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4" x-data="saleForm()" x-init="init()">
        
        <form action="{{ route('sales.store') }}" method="POST"
            class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100 transition-all">
            @csrf

            <x-ui.toasts />
            
            {{-- HEADER --}}
            <div class="bg-white border-b border-gray-100 p-6 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 tracking-tight">Nueva Venta de Mercancía</h2>
                    <p class="text-sm text-gray-500">Gestión de facturación y salida de inventario.</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Fecha de Emisión</span>
                    <input type="date" name="sale_date" x-model="formData.sale_date"
                        class="border-none p-0 text-gray-600 font-mono text-sm bg-transparent focus:ring-0 text-right cursor-default" 
                        readonly>
                </div>
            </div>

            <div class="p-6 md:p-8 space-y-8">
                
                {{-- SECCIÓN 1: CONFIGURACIÓN --}}
                <section class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <x-input-label value="Tipo de Venta" class="mb-1 text-xs text-gray-500 uppercase tracking-wider" />
                            <select name="payment_type" x-model="formData.payment_type" @change="handlePaymentTypeChange()"
                                    class="w-full border-gray-300 rounded-lg text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all">
                                @foreach($payment_types as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Almacén de Salida" class="mb-1 text-xs text-gray-500 uppercase tracking-wider" />
                            <select name="warehouse_id" x-model="formData.warehouse_id" @change="clearItems()"
                                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all" required>
                                <option value="">Seleccione...</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Cliente --}}
                        <div class="md:col-span-1">
                            <x-input-label value="Cliente" class="mb-1 text-xs text-gray-500 uppercase tracking-wider" />
                            <select name="client_id" x-model="formData.client_id" @change="validateNcfSupport()"
                                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all" required>
                                <template x-for="client in filteredClients" :key="client.id">
                                    <option :value="client.id" x-text="client.name"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Tipo de Comprobante (NCF) --}}
                        <div class="md:col-span-1">
                            <x-input-label value="Comprobante Fiscal" class="mb-1 text-xs text-gray-500 uppercase tracking-wider" />
                            <select name="ncf_type_id" x-model="formData.ncf_type_id"
                                class="w-full border-gray-300 rounded-lg text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all" required>
                                <template x-for="type in filteredNcfTypes" :key="type.id">
                                    <option :value="type.id" x-text="type.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    {{-- INFO BOX DEL CLIENTE --}}
                    <div class="min-h-[70px]">
                        <template x-if="selectedClient">
                            <div class="grid grid-cols-1 gap-4 transition-all duration-300"
                                :class="selectedClient.id == 1 ? 'md:grid-cols-1' : 'md:grid-cols-2'"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0">
                                
                                {{-- Columna 1: Estatus --}}
                                <div :class="selectedClient.id == 1 ? 'bg-blue-50 border-blue-200' : (selectedClient.is_moroso || selectedClient.is_blocked ? 'bg-red-50 border-red-200' : 'bg-emerald-50 border-emerald-200')" 
                                    class="border rounded-xl p-4 flex items-center gap-4 transition-all duration-500">
                                    
                                    <div :class="selectedClient.id == 1 ? 'bg-blue-500' : (selectedClient.is_moroso || selectedClient.is_blocked ? 'bg-red-500' : 'bg-emerald-500')" 
                                        class="w-1.5 h-10 rounded-full shadow-sm"></div>
                                    
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider">Estatus del Cliente</p>
                                        <p :class="selectedClient.id == 1 ? 'text-blue-700' : (selectedClient.is_moroso || selectedClient.is_blocked ? 'text-red-700' : 'text-emerald-700')" 
                                        class="font-bold text-sm uppercase">
                                            <template x-if="selectedClient.id == 1">
                                                <span>Comprobante de Consumo (Público General)</span>
                                            </template>
                                            <template x-if="selectedClient.id != 1">
                                                <span x-text="selectedClient.status_name"></span>
                                            </template>
                                            <template x-if="selectedClient.is_moroso"><span> (SÓLO CONTADO)</span></template>
                                        </p>
                                    </div>
                                </div>

                                {{-- Columna 2: Línea de Crédito (Solo si NO es consumidor final) --}}
                                <template x-if="selectedClient.id != 1">
                                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-center justify-between group hover:bg-indigo-100 transition-colors cursor-default">
                                        <div>
                                            <p class="text-[10px] uppercase font-bold text-indigo-400 tracking-wider">Línea de Crédito Disponible</p>
                                            <p class="text-indigo-900 font-black text-lg font-mono" x-text="formatMoney(selectedClient.available)"></p>
                                        </div>
                                        <x-heroicon-o-credit-card class="w-8 h-8 text-indigo-300 group-hover:scale-110 transition-transform"/>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    {{-- ALERTA DE RESTRICCIÓN --}}
                    <template x-if="selectedClient && selectedClient.is_moroso && formData.payment_type === 'credit'">
                        <div x-transition class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center gap-4 text-amber-800 shadow-sm animate-pulse">
                            <div class="bg-amber-100 p-2 rounded-lg">
                                <x-heroicon-s-exclamation-circle class="w-6 h-6 text-amber-600"/>
                            </div>
                            <div class="text-sm">
                                <strong class="block font-bold">Restricción de Crédito</strong>
                                <p class="opacity-80">El cliente tiene facturas vencidas. Cambie a <strong>Contado</strong>.</p>
                            </div>
                        </div>
                    </template>

                    {{-- ALERTA DE RNC FALTANTE PARA CRÉDITO FISCAL --}}
                    <template x-if="ncfRequiresRnc && !selectedClient?.tax_id">
                        <div x-transition class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-4 text-red-800 shadow-sm mb-4">
                            <div class="bg-red-100 p-2 rounded-lg">
                                <x-heroicon-s-exclamation-triangle class="w-6 h-6 text-red-600"/>
                            </div>
                            <div class="text-sm">
                                <strong class="block font-bold">RNC Requerido</strong>
                                <p class="opacity-80">Este cliente no tiene RNC configurado. El tipo de comprobante seleccionado lo requiere.</p>
                            </div>
                        </div>
                    </template>
                </section>

                {{-- SECCIÓN 2: DETALLE DE PRODUCTOS --}}
                <section x-show="formData.warehouse_id" 
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">
                    
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-400 uppercase text-[10px] tracking-widest flex items-center gap-2">
                            <span class="w-5 h-5 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center text-[10px] font-bold">2</span>
                            Detalle de Productos
                        </h3>
                        <button type="button" @click="addItem()"
                            class="text-xs bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all font-bold active:scale-95">
                            + Añadir Producto
                        </button>
                    </div>

                    <div class="border border-gray-100 rounded-xl overflow-hidden shadow-sm bg-white">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50/80 text-gray-500 text-[10px] uppercase text-left border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 w-1/3 text-center">Producto</th>
                                    <th class="px-6 py-4 text-center">Stock</th>
                                    <th class="px-6 py-4 w-28 text-center">Cant.</th>
                                    <th class="px-6 py-4 text-center">Precio</th>
                                    <th class="px-6 py-4 text-right">Subtotal</th>
                                    <th class="px-6 py-4 w-10"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="hover:bg-gray-50/50 transition-colors group"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 -translate-x-2"
                                        x-transition:enter-end="opacity-100 translate-x-0">
                                        
                                        <td class="px-4 py-3">
                                            <select :name="`items[${index}][product_id]`" 
                                                    x-model="item.product_id"
                                                    @change="updateProductData(index)"
                                                    class="w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 transition-all">
                                                <option value="">Seleccione...</option>
                                                <template x-for="p in filteredProducts" :key="p.id">
                                                    <option :value="p.id" x-text="p.name"></option>
                                                </template>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2 py-1 bg-gray-100 rounded text-gray-500 font-mono text-[11px]" x-text="item.stock || 0"></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" :name="`items[${index}][quantity]`" 
                                                x-model.number="item.quantity"
                                                @input="calculateTotals()"
                                                :max="item.stock" min="1"
                                                class="w-full border-gray-200 rounded-lg text-sm text-center focus:ring-indigo-500">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" :name="`items[${index}][price]`" 
                                                x-model.number="item.price"
                                                class="w-full border-transparent bg-gray-50/50 rounded-lg text-sm text-right font-mono cursor-not-allowed" 
                                                readonly>
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono font-bold text-gray-700" 
                                            x-text="formatMoney(item.quantity * item.price)">
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button type="button" @click="removeItem(index)" 
                                                    class="p-1.5 text-red-300 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                <x-heroicon-s-trash class="w-4 h-4"/>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                {{-- EMPTY STATE --}}
                                <template x-if="items.length === 0">
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic text-sm">
                                            No hay productos agregados. Haga clic en "Añadir Producto" para comenzar.
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- SECCIÓN 3: TOTALES --}}
                <section class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-2">
                        <x-input-label value="Notas de la Venta" class="text-xs text-gray-500 uppercase tracking-wider" />
                        <textarea name="notes" rows="3" 
                                class="w-full mt-2 border-gray-300 rounded-xl text-sm focus:ring-indigo-500 transition-all" 
                                placeholder="Detalles adicionales de la factura..."></textarea>
                    </div>

                    <div class="bg-gray-900 text-white rounded-2xl p-6 shadow-2xl space-y-4 relative overflow-hidden transition-all duration-500">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-500/10 rounded-full -mr-12 -mt-12"></div>
                        
                        {{-- Subtotal (Venta Real) --}}
                        <div class="flex justify-between text-[10px] opacity-50 uppercase tracking-[0.2em]">
                            <span>Venta Neta (Subtotal)</span>
                            <span x-text="formatMoney(totals.subtotal)"></span>
                        </div>

                        {{-- Toggle de Impuesto: Solo aparece si el tax_rate es > 0 --}}
                        {{-- COMENTADA HASTA SABER EL FUNCIONAMIENTO CORRECTO DE LAS EMPRESAS --}}
                        {{-- <template x-if="config.tax_rate > 0">
                            <div class="flex justify-between items-center py-3 border-y border-white/5">
                                <div class="flex items-center gap-3">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="config.apply_tax" @change="calculateTotals()" class="sr-only peer">
                                        <div class="w-9 h-5 bg-gray-700 rounded-full peer peer-checked:bg-indigo-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div>
                                    </label>
                                    <span class="text-[10px] font-bold text-gray-400">DESGLOSAR ITBIS</span>
                                </div>
                                <span class="font-mono text-sm text-indigo-300" x-text="formatMoney(totals.tax)"></span>
                            </div>
                        </template> --}}

                        {{-- NUEVO: Bloque de Pago (Solo si es Contado) --}}
                        <template x-if="formData.payment_type === 'cash'">
                            <div class="space-y-3 pt-4 mt-2 border-t border-white/10" x-transition>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Efectivo Recibido</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-mono">$</span>
                                        <input type="number" name="cash_received" x-model.number="formData.cash_received" 
                                            @input="calculateChange()" step="0.01"
                                            class="w-full bg-white/5 border-white/10 rounded-lg pl-7 py-2 text-lg font-mono focus:ring-indigo-500 focus:bg-white/10 transition-all">
                                    </div>
                                </div>

                                <div class="flex justify-between items-center bg-indigo-500/20 p-3 rounded-xl border border-indigo-500/30">
                                    <span class="text-[10px] font-bold text-indigo-300 uppercase">Cambio a Devolver</span>
                                    <span class="text-xl font-black font-mono text-indigo-400" x-text="formatMoney(formData.cash_change)"></span>
                                    <input type="hidden" name="cash_change" :value="formData.cash_change">
                                </div>
                            </div>
                        </template>

                        <div class="pt-2">
                            <div class="flex justify-between items-end">
                                <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Total a Pagar</span>
                                <span class="text-3xl font-black font-mono tracking-tight" x-text="formatMoney(totals.total)"></span>
                            </div>
                            {{-- Enviamos los valores reales al servidor --}}
                            <input type="hidden" name="subtotal" :value="totals.subtotal">
                            <input type="hidden" name="tax_amount" :value="totals.tax">
                            <input type="hidden" name="total_amount" :value="totals.total">
                        </div>
                    </div>
                </section>
            </div>

            {{-- FOOTER --}}
            <div class="p-6 bg-gray-50/50 flex justify-end items-center border-t border-gray-100 gap-6">
                <a href="{{ route('sales.index') }}" class="text-sm font-medium text-gray-400 hover:text-gray-600 transition-colors">Cancelar</a>
                
                <button type="submit"
                    class="bg-indigo-600 text-white px-10 py-3 rounded-xl font-bold shadow-lg shadow-indigo-100 transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:grayscale"
                    :class="isSubmitDisabled ? '' : 'hover:bg-indigo-700 hover:-translate-y-0.5 active:scale-95'"
                    :disabled="isSubmitDisabled">
                    <x-heroicon-s-check-circle class="w-5 h-5"/>
                    Confirmar y Facturar
                </button>
            </div>
        </form>
    </div>
    <script>
        function saleForm() {
            return {
                products: @json($products),
                clients: @json($clients),
                ncf_types: @json($ncf_types),
                items: [],
                config: {
                    tax_rate: {{ general_config()->impuesto->valor ?? 0 }},
                    apply_tax: false,
                },
                formData: {
                    payment_type: 'cash',
                    client_id: '1', // Por defecto Consumidor Final
                    warehouse_id: '',
                    ncf_type_id: '2', // Por defecto Consumidor Final (B02)
                    sale_date: '{{ date("Y-m-d") }}',
                    cash_received: 0,
                    cash_change: 0,
                },  
                totals: { subtotal: 0, tax: 0, total: 0 },  

                init() {
                    this.$watch('formData.ncf_type_id', () => this.validateNcfAndClient());
                },

                get filteredClients() {
                    let list = this.clients;
                    
                    // Si es Crédito, quitar Consumidor Final (ID 1)
                    if (this.formData.payment_type === 'credit') {
                        list = list.filter(c => c.id != 1);
                    }

                    // Si el NCF actual requiere RNC, quitar Consumidor Final o genéricos
                    const selectedNcf = this.ncf_types.find(n => n.id == this.formData.ncf_type_id);
                    if (selectedNcf && ['01', '31'].includes(selectedNcf.code)) {
                        list = list.filter(c => c.id != 1 && c.tax_id !== '00000000000');
                    }

                    return list;
                },

                // Tipos de NCF filtrados según el cliente seleccionado
                get filteredNcfTypes() {
                    // Si el cliente es Consumidor Final, no mostrar Crédito Fiscal (01, 31)
                    if (this.formData.client_id == 1 || this.selectedClient?.tax_id === '00000000000') {
                        return this.ncf_types.filter(n => !['01', '31'].includes(n.code));
                    }
                    return this.ncf_types;
                },

                get selectedClient() {
                    return this.clients.find(c => c.id == this.formData.client_id) || null;
                },

                get filteredProducts() {
                    if (!this.formData.warehouse_id) return [];
                    return this.products.filter(p => p.warehouse_id == this.formData.warehouse_id);
                },

                // Nueva lógica de validación cruzada
                validateNcfAndClient() {
                    const selectedNcf = this.ncf_types.find(n => n.id == this.formData.ncf_type_id);
                    
                    // Si el NCF pide RNC y el cliente actual no tiene o es Consumidor Final, limpiar cliente
                    if (selectedNcf && ['01', '31'].includes(selectedNcf.code)) {
                        if (this.formData.client_id == 1 || (this.selectedClient && !this.selectedClient.tax_id)) {
                            this.formData.client_id = '';
                        }
                    }
                },

                get ncfRequiresRnc() {
                    const selectedNcf = this.ncf_types.find(n => n.id == this.formData.ncf_type_id);
                    if (!selectedNcf) return false;
                    return ['01', '31'].includes(selectedNcf.code) && (!this.selectedClient?.tax_id || this.selectedClient.tax_id === '00000000000');
                },

                get isSubmitDisabled() {
                    const hasItems = this.items.length > 0;
                    const hasTotal = this.totals.total > 0;
                    
                    // Bloqueo si el NCF requiere un RNC válido y el cliente no lo tiene o es el genérico
                    if (this.ncfRequiresRnc) {
                        return true; 
                    }

                    // Bloqueo por morosidad en ventas a crédito
                    if (this.formData.payment_type === 'credit') {
                        return !hasItems || !this.selectedClient || this.selectedClient.is_moroso;
                    }

                    return !hasItems || !hasTotal;
                },

                addItem() {
                    this.items.push({ product_id: '', quantity: 1, price: 0, stock: 0 });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calculateTotals();
                },

                clearItems() {
                    this.items = [];
                    this.calculateTotals();
                },

                updateProductData(index) {
                    const item = this.items[index];
                    const product = this.products.find(p => p.id == item.product_id && p.warehouse_id == this.formData.warehouse_id);
                    if (product) {
                        item.price = product.price;
                        item.stock = product.stock;
                    }
                    this.calculateTotals();
                },

                calculateTotals() {
                    // 1. El Total Bruto es la suma simple de lo que el cliente realmente va a pagar
                    const bruto = this.items.reduce((sum, item) => sum + (parseFloat(item.quantity || 0) * parseFloat(item.price || 0)), 0);
                    
                    if (this.config.apply_tax && this.config.tax_rate > 0) {
                        // Lógica ITBIS Incluido:
                        // Total es 100. Base = 100 / 1.18. Impuesto = Total - Base.
                        const divisor = 1 + (this.config.tax_rate / 100);
                        this.totals.total = bruto; 
                        this.totals.subtotal = bruto / divisor;
                        this.totals.tax = bruto - this.totals.subtotal;
                    } else {
                        // Sin impuestos o impuesto 0: Todo es subtotal
                        this.totals.total = bruto;
                        this.totals.subtotal = bruto;
                        this.totals.tax = 0;
                        this.calculateChange();
                    }
                },

                calculateChange() {
                    const received = parseFloat(this.formData.cash_received || 0);
                    const total = parseFloat(this.totals.total || 0);
                    
                    if (received >= total) {
                        this.formData.cash_change = (received - total).toFixed(2);
                    } else {
                        this.formData.cash_change = 0;
                    }
                },

                formatMoney(amount) {
                    return '$' + new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(amount);
                }
            }
        }
    </script>
</x-app-layout>