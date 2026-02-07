<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4" 
         x-data="saleForm()" 
         x-init="init()">
        
        <form action="{{ route('sales.store') }}" method="POST"
            class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
            @csrf

            <x-ui.toasts />
            
            <x-form-header
                title="Nueva Venta de Mercancía"
                subtitle="Registro de salida de inventario y facturación."
                :back-route="route('sales.index')" />

            <div class="p-6 md:p-8 space-y-8">
                
                {{-- SECCIÓN 1: CABECERA Y CLIENTE --}}
                <section class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-gray-50/50 p-6 rounded-xl border border-gray-100">
                    <div class="md:col-span-2">
                        <x-input-label value="Cliente" />
                        <select name="client_id" x-model="formData.client_id" 
                                class="w-full mt-1 border-gray-300 rounded-lg text-sm focus:ring-indigo-500" required>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">
                                    {{ $client->name }} (Crédito Disp: ${{ number_format($client->credit_limit - $client->balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-1">
                        <x-input-label value="Almacén de Salida" />
                        <select name="warehouse_id" x-model="formData.warehouse_id" @change="clearItems()"
                                class="w-full mt-1 border-gray-300 rounded-lg text-sm focus:ring-indigo-500" required>
                            <option value="">Seleccione...</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-1">
                        <x-input-label value="Fecha de Registro" />
                        <x-text-input 
                            type="date" 
                            name="sale_date"
                            x-model="formData.sale_date"
                            class="w-full mt-1 bg-gray-100 cursor-not-allowed opacity-75" 
                            readonly
                            required 
                        />
                        <p class="text-[10px] text-gray-400 mt-1 italic">* Fecha automática no editable</p>
                    </div>
                </section>

                {{-- SECCIÓN 2: DETALLE DE PRODUCTOS --}}
                <section>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider flex items-center gap-2">
                            <span class="w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-[10px]">2</span>
                            Productos / Servicios
                        </h3>
                        <button type="button" @click="addItem()" :disabled="!formData.warehouse_id"
                            class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-lg hover:bg-indigo-600 hover:text-white transition-all font-bold disabled:opacity-50">
                            + Añadir Producto
                        </button>
                    </div>

                    <div class="border rounded-xl overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 text-gray-600 text-xs uppercase text-left">
                                <tr>
                                    <th class="px-4 py-3 w-1/3">Producto</th>
                                    <th class="px-4 py-3 text-center">Cant. Disp.</th>
                                    <th class="px-4 py-3 w-24">Cantidad</th>
                                    <th class="px-4 py-3">Precio</th>
                                    <th class="px-4 py-3 text-right">Subtotal</th>
                                    <th class="px-4 py-3 w-10"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="p-2">
                                            <select :name="`items[${index}][product_id]`" 
                                                    x-model="item.product_id"
                                                    @change="updateProductData(index)"
                                                    class="w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500" required>
                                                <option value="">Seleccione producto...</option>
                                                <template x-for="p in filteredProducts" :key="p.id">
                                                    <option :value="p.id" x-text="p.name"></option>
                                                </template>
                                            </select>
                                        </td>
                                        <td class="p-2 text-center text-gray-500 font-mono" x-text="item.stock || 0"></td>
                                        <td class="p-2">
                                            <input type="number" :name="`items[${index}][quantity]`" 
                                                x-model.number="item.quantity"
                                                @input="calculateTotals()"
                                                :max="item.stock"
                                                min="1"
                                                class="w-full border-gray-200 rounded-lg text-sm text-center focus:ring-indigo-500" required>
                                        </td>
                                        <td class="p-2">
                                            <input type="number" :name="`items[${index}][price]`" 
                                                x-model.number="item.price"
                                                @input="calculateTotals()"
                                                step="0.01"
                                                readonly
                                                class="w-full border-transparent bg-gray-50 rounded-lg text-sm text-right font-mono cursor-not-allowed focus:ring-0" 
                                                required>
                                        </td>
                                        <td class="p-2 text-right font-mono font-bold text-gray-700" 
                                            x-text="formatMoney(item.quantity * item.price)">
                                        </td>
                                        <td class="p-2 text-center">
                                            <button type="button" @click="removeItem(index)"
                                                class="text-red-400 hover:text-red-600 transition">
                                                <x-heroicon-s-trash class="w-5 h-5"/>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- SECCIÓN 3: PAGO Y TOTALES --}}
                <section class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                    <div class="md:col-span-2 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Tipo de Pago" />
                                <select name="payment_type" class="w-full mt-1 border-gray-300 rounded-lg text-sm">
                                    @foreach($payment_types as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label value="Notas de la Venta" />
                                <textarea name="notes" rows="2" class="w-full mt-1 border-gray-300 rounded-lg text-sm" placeholder="Opcional..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-900 text-white rounded-2xl p-6 shadow-xl space-y-4">
                        <div class="flex justify-between text-sm opacity-70">
                            <span>Subtotal</span>
                            <span x-text="formatMoney(totals.subtotal)"></span>
                        </div>

                        {{-- Toggle de Impuestos --}}
                        <div class="flex justify-between items-center py-2 border-y border-white/5">
                            <div class="flex items-center gap-2">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="config.apply_tax" @change="calculateTotals()" class="sr-only peer">
                                    <div class="w-9 h-5 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-500"></div>
                                </label>
                                <span class="text-xs uppercase font-medium">Aplicar ITBIS ({{ general_config()->impuesto->valor }}%)</span>
                            </div>
                            <span class="font-mono text-sm" x-text="formatMoney(totals.tax)"></span>
                            {{-- Campo oculto para enviar si se aplicó impuesto o no al backend --}}
                            <input type="hidden" name="apply_tax" :value="config.apply_tax ? 1 : 0">
                        </div>

                        <div class="pt-2 flex justify-between items-center">
                            <span class="text-xs uppercase font-bold tracking-widest text-indigo-400">Total a Pagar</span>
                            <span class="text-3xl font-black font-mono" x-text="formatMoney(totals.total)"></span>
                            <input type="hidden" name="total_amount" :value="totals.total">
                        </div>
                    </div>
                </section>
            </div>

            <div class="p-6 bg-gray-50 flex justify-end items-center border-t gap-4">
                <a href="{{ route('sales.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Cancelar</a>
                <x-primary-button class="bg-indigo-600 px-10 py-3" ::disabled="items.length === 0 || totals.total <= 0">
                    Finalizar Venta
                </x-primary-button>
            </div>
        </form>
    </div>

    <script>
        function saleForm() {
            return {
                products: @json($products),
                items: [],
                // Cargamos la configuración desde el helper de Laravel hacia Alpine
                config: {
                    tax_rate: {{ general_config()->impuesto->valor ?? 0 }},
                    apply_tax: false, // Por defecto apagado para ventas pequeñas
                },
                formData: {
                    client_id: '{{ $clients->first()->id ?? "" }}',
                    warehouse_id: '',
                    sale_date: '{{ date("Y-m-d") }}',
                },  
                totals: {
                    subtotal: 0,
                    tax: 0,
                    total: 0
                },

                init() {
                    if(this.formData.warehouse_id) this.addItem();
                },

                get filteredProducts() {
                    if (!this.formData.warehouse_id) return [];
                    return this.products.filter(p => p.warehouse_id == this.formData.warehouse_id);
                },

                addItem() {
                    this.items.push({
                        product_id: '',
                        quantity: 1,
                        price: 0,
                        stock: 0
                    });
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
                    // 1. Calcular Subtotal
                    this.totals.subtotal = this.items.reduce((sum, item) => {
                        return sum + (parseFloat(item.quantity || 0) * parseFloat(item.price || 0));
                    }, 0);
                    
                    // 2. Calcular Impuesto basado en el Switch
                    this.totals.tax = this.config.apply_tax 
                        ? (this.totals.subtotal * (this.config.tax_rate / 100)) 
                        : 0;

                    // 3. Total Final
                    this.totals.total = this.totals.subtotal + this.totals.tax;
                },

                formatMoney(amount) {
                    return '$' + new Intl.NumberFormat('en-US', { 
                        minimumFractionDigits: 2, 
                        maximumFractionDigits: 2 
                    }).format(amount);
                }
            }
        }
    </script>
</x-app-layout>