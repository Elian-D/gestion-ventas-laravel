@foreach($products as $item)
    {{-- MODAL: DETALLES COMPLETOS DEL PRODUCTO --}}
    <x-modal name="view-product-{{ $item->id }}" maxWidth="2xl">
        <div class="overflow-hidden rounded-xl">
            {{-- Header con degradado e Identificación --}}
            <div class="bg-gradient-to-r from-gray-50 to-white px-8 py-6 border-b relative">
                <div class="flex justify-between items-start">
                    <div class="flex gap-4 items-center">
                        <div class="relative">
                            @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-16 h-16 rounded-xl object-cover shadow-md border-2 border-white">
                            @else
                                <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-700 shadow-sm border-2 border-white">
                                    <x-heroicon-s-photo class="w-8 h-8"/>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 leading-tight">{{ $item->name }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs font-mono font-semibold px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded border border-indigo-100 uppercase tracking-wider">
                                    {{ $item->sku }}
                                </span>
                                <span class="text-gray-400 text-xs">•</span>
                                <span class="text-sm text-gray-500 font-medium italic">{{ $item->category->name ?? 'Sin categoría' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Badge de Estado Activo --}}
                    @if($item->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold ring-1 ring-inset shadow-sm bg-emerald-100 text-emerald-700 ring-emerald-600/20">
                            <span class="w-1.5 h-1.5 rounded-full mr-2 bg-emerald-500 animate-pulse"></span>
                            Activo
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold ring-1 ring-inset shadow-sm bg-red-100 text-red-700 ring-red-600/20">
                            <span class="w-1.5 h-1.5 rounded-full mr-2 bg-red-500"></span>
                            Inactivo
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-8 bg-white">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- Columna Izquierda: Precios y Costos --}}
                    <div class="space-y-6">
                        <section>
                            <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-emerald-50 pb-2">
                                <x-heroicon-s-currency-dollar class="w-4 h-4"/> Análisis Financiero
                            </h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <span class="text-[10px] text-gray-400 uppercase font-bold block">Precio Venta</span>
                                    <p class="text-lg font-bold text-gray-800">
                                        {{ general_config()->currency_symbol }} {{ number_format($item->price, 2) }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <span class="text-[10px] text-gray-400 uppercase font-bold block">Costo Base</span>
                                    <p class="text-lg font-bold text-gray-600">
                                        {{ general_config()->currency_symbol }} {{ number_format($item->cost, 2) }}
                                    </p>
                                </div>
                            </div>
                            @if($item->price > 0)
                                <div class="mt-3 px-3">
                                    @php $margin = (($item->price - $item->cost) / $item->price) * 100; @endphp
                                    <span class="text-[11px] text-gray-500 uppercase">Margen de utilidad: 
                                        <span class="font-bold {{ $margin > 20 ? 'text-emerald-600' : 'text-amber-600' }}">
                                            {{ number_format($margin, 1) }}%
                                        </span>
                                    </span>
                                </div>
                            @endif
                        </section>

                        <section>
                            <h4 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-blue-50 pb-2">
                                <x-heroicon-s-information-circle class="w-4 h-4"/> Especificaciones
                            </h4>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                                        <x-heroicon-s-tag class="w-4 h-4"/>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase block">Unidad de Medida</span>
                                        <p class="text-sm font-medium text-gray-700">{{ $item->unit->name ?? 'No definida' }} ({{ $item->unit->abbreviation ?? '—' }})</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-gray-50 rounded-lg text-gray-400">
                                        <x-heroicon-s-document-text class="w-4 h-4"/>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase block">Descripción</span>
                                        <p class="text-sm text-gray-600 leading-relaxed">{{ $item->description ?: 'Sin descripción adicional.' }}</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    {{-- Columna Derecha: Inventario y Auditoría --}}
                    <div class="space-y-6">
                        <section>
                            <h4 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-amber-50 pb-2">
                                <x-heroicon-s-cube class="w-4 h-4"/> Gestión de Inventario
                            </h4>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                @if($item->is_stockable)
                                    <div class="flex justify-between items-end mb-4">
                                        <div>
                                            <span class="text-[10px] text-gray-400 uppercase block">Stock Actual</span>
                                            <p class="text-2xl font-black {{ $item->stock <= $item->min_stock ? 'text-red-600' : 'text-gray-800' }}">
                                                {{ $item->stock }} <span class="text-sm font-medium text-gray-400">{{ $item->unit->abbreviation ?? '' }}</span>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[10px] text-gray-400 uppercase block">Mínimo</span>
                                            <p class="text-sm font-bold text-gray-600">{{ $item->min_stock }}</p>
                                        </div>
                                    </div>
                                    {{-- Barra de progreso visual --}}
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        @php $percent = $item->stock > 0 ? min(($item->stock / ($item->min_stock * 3)) * 100, 100) : 0; @endphp
                                        <div class="h-1.5 rounded-full {{ $item->stock <= $item->min_stock ? 'bg-red-500' : 'bg-emerald-500' }}" style="width: {{ $percent }}%"></div>
                                    </div>
                                @else
                                    <div class="py-4 text-center">
                                        <p class="text-xs font-bold text-gray-400 uppercase">Producto no inventariable</p>
                                        <p class="text-[10px] text-gray-400 italic">Ventas ilimitadas sin control de stock</p>
                                    </div>
                                @endif
                            </div>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-gray-50 pb-2">
                                <x-heroicon-s-clock class="w-4 h-4"/> Historial de Registro
                            </h4>
                            <div class="space-y-3 px-1">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-400">Creado el:</span>
                                    <span class="font-medium text-gray-600">{{ $item->created_at->format('d/m/Y h:i A') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-400">Modificado:</span>
                                    <span class="font-medium text-gray-600">{{ $item->updated_at->diffForHumans() }}</span>
                                </div>
                                <div class="mt-4 pt-4 border-t border-dashed border-gray-100 flex items-center gap-2">
                                    <span class="text-[10px] px-2 py-0.5 bg-gray-100 text-gray-500 rounded font-mono">UUID: {{ Str::limit($item->id, 18) }}</span>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                {{-- Footer con acciones --}}
                <div class="mt-10 pt-6 border-t flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                         <span class="text-[10px] text-gray-300 uppercase tracking-tighter font-mono italic text-center sm:text-left">
                            Cátalogo de Productos v1.0
                         </span>
                    </div>
                    <div class="flex gap-3 w-full sm:w-auto">
                        <x-secondary-button x-on:click="$dispatch('close')" class="flex-1 sm:flex-none justify-center">
                            Cerrar
                        </x-secondary-button>
                        <a href="{{ route('products.edit', $item) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition duration-150 shadow-md shadow-indigo-100">
                            <x-heroicon-s-pencil class="w-4 h-4 mr-2" /> Editar Producto
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-modal>

    {{-- Modal de Confirmación de Eliminación --}}
    <x-ui.confirm-deletion-modal 
        :id="$item->id"
        :title="'¿Eliminar Producto?'"
        :itemName="$item->name"
        :type="'el producto'"
        :route="route('products.destroy', $item)"
        >
        <strong>Atención:</strong> Si elimina este producto, no podrá ser seleccionado en futuras ventas del POS.
    </x-ui.confirm-deletion-modal>
@endforeach