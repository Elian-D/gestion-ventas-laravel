<x-modal name="register-production" maxWidth="md">
    <x-form-header 
        title="Registro de Producción" 
        subtitle="Incremente el stock por generación de producto terminado." />

    <form action="{{ route('inventory.movements.store') }}" method="POST" class="p-6">
        @csrf
        {{-- Forzamos que sea de tipo entrada --}}
        <input type="hidden" name="type" value="input">

        <div class="space-y-4">
            {{-- Producto --}}
            <div>
                <x-input-label for="prod_product_id" value="Producto Terminado" />
                <select name="product_id" id="prod_product_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                    <option value="">Seleccione el producto generado...</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Almacén donde entra la producción --}}
            <div>
                <x-input-label for="prod_warehouse_id" value="Almacén de Recepción" />
                <select name="warehouse_id" id="prod_warehouse_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                    <option value="">Seleccione almacén destino...</option>
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Cantidad --}}
            <div>
                <x-input-label for="prod_quantity" value="Cantidad Producida" />
                <x-text-input id="prod_quantity" name="quantity" type="number" step="0.01" min="0.01"
                    class="mt-1 block w-full" placeholder="0.00" required />
                <p class="mt-1 text-[10px] text-indigo-500 italic font-medium">Se sumará directamente al balance del almacén seleccionado.</p>
            </div>

            {{-- Descripción --}}
            <div>
                <x-input-label for="prod_description" value="Notas de Producción" />
                <textarea name="description" id="prod_description" rows="2" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" 
                    placeholder="Ej: Lote #105 - Turno Mañana" required></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
            <x-primary-button class="bg-indigo-600">Finalizar Ingreso</x-primary-button>
        </div>
    </form>
</x-modal>