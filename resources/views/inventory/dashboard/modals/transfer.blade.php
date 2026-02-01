<x-modal name="register-transfer" maxWidth="md">
    <x-form-header 
        title="Transferencia de Stock" 
        subtitle="Mueva productos entre sus nodos logísticos." />

    <form action="{{ route('inventory.movements.store') }}" 
          method="POST" 
          class="p-6"
          x-data="{ originWh: '' }">
        @csrf
        <input type="hidden" name="type" value="transfer">

        <div class="space-y-4">
            {{-- Producto --}}
            <div>
                <x-input-label for="trans_product_id" value="Producto a Mover" />
                <select name="product_id" id="trans_product_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                    <option value="">Seleccione producto...</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Almacén Origen --}}
                <div>
                    <x-input-label for="trans_warehouse_id" value="Origen" />
                    <select name="warehouse_id" id="trans_warehouse_id" x-model="originWh"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                        <option value="">Seleccione...</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Almacén Destino --}}
                <div>
                    <x-input-label for="trans_to_warehouse_id" value="Destino" />
                    <select name="to_warehouse_id" id="trans_to_warehouse_id" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                        <option value="">Seleccione...</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" x-show="originWh != {{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Cantidad --}}
            <div>
                <x-input-label for="trans_quantity" value="Cantidad a Transferir" />
                <x-text-input id="trans_quantity" name="quantity" type="number" step="0.01" min="0.01"
                    class="mt-1 block w-full" placeholder="0.00" required />
            </div>

            {{-- Descripción --}}
            <div>
                <x-input-label for="trans_description" value="Referencia" />
                <textarea name="description" id="trans_description" rows="2" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" 
                    placeholder="Ej: Reabastecimiento sucursal norte" required></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
            <x-primary-button class="bg-indigo-600">Ejecutar Transferencia</x-primary-button>
        </div>
    </form>
</x-modal>