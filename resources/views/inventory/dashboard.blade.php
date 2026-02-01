<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-[1600px] mx-auto">
        
        {{-- Header con acciones rápidas --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Centro de Inventario</h2>
                <p class="text-sm text-gray-500 mt-1">Visión general del sistema logístico</p>
            </div>
            
            <div class="flex gap-3">
                <button @click="$dispatch('open-modal', 'register-production')" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-indigo-700 transition">
                    <x-heroicon-s-plus-circle class="w-5 h-5"/>
                    Registrar Producción
                </button>
                
                <button @click="$dispatch('open-modal', 'register-transfer')" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-lg border border-gray-300 shadow-sm hover:bg-gray-50 transition">
                    <x-heroicon-s-arrows-right-left class="w-5 h-5"/>
                    Transferir
                </button>
            </div>
        </div>

        <x-ui.toasts />

    @include('inventory.dashboard.kpi-cards')


        {{-- Layout principal: Gráficos + Actividad reciente --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
                        
            {{-- Columna principal: Gráficos (2/3 del ancho) --}}
            <div class="xl:col-span-2 space-y-6">
                
                {{-- 1. Flujo de Inventario --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Flujo de Inventario</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Últimos 7 días</p>
                        </div>
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 text-xs font-semibold rounded-full">Semanal</span>
                    </div>
                    <div class="h-[280px]">
                        <canvas id="chart-movements"></canvas>
                    </div>
                </div>

                {{-- Grid de gráficos secundarios --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- 2. Stock por Almacén --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">Stock por Almacén</h3>
                        <div class="h-[240px] relative">
                            <canvas id="chart-warehouses"></canvas>
                        </div>
                    </div>

                    {{-- 3. Mayor Rotación --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">Mayor Rotación</h3>
                        <div class="h-[240px]">
                            <canvas id="chart-top-products"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar: Actividad reciente (1/3 del ancho) --}}
            <div class="space-y-6">
                
                {{-- Últimos Movimientos --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-900">Actividad Reciente</h3>
                        <a href="{{ route('inventory.movements.index') }}" 
                           class="text-xs text-indigo-600 hover:text-indigo-700 font-semibold hover:underline">
                            Ver kardex
                        </a>
                    </div>
                    
                    <div class="space-y-3">
                        @forelse($recentMovements as $mv)
                            <div class="flex items-start gap-3 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                                <div class="flex-shrink-0 p-2 rounded-lg {{ $mv->quantity > 0 ? 'bg-green-50' : 'bg-red-50' }}">
                                    @if($mv->quantity > 0)
                                        <x-heroicon-s-arrow-up-circle class="w-5 h-5 text-green-600"/>
                                    @else
                                        <x-heroicon-s-arrow-down-circle class="w-5 h-5 text-red-600"/>
                                    @endif
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $mv->product->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $mv->warehouse->name }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 uppercase mt-1">
                                        {{ $mv->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                
                                <div class="flex-shrink-0 text-right">
                                    <span class="font-mono font-bold text-sm {{ $mv->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $mv->quantity > 0 ? '+' : '' }}{{ number_format(abs($mv->quantity), 0) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <x-heroicon-o-inbox class="w-12 h-12 text-gray-300 mx-auto mb-2"/>
                                <p class="text-sm text-gray-500">No hay movimientos recientes</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Lista de Stock Bajo/Crítico --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-900">Estado de Inventario</h3>
                        <span class="px-2 py-0.5 bg-red-100 text-red-600 text-[10px] font-bold uppercase rounded">Crítico</span>
                    </div>

                    <div class="space-y-4">
                        @forelse($lowStockProducts as $stock)
                            <div class="flex items-center justify-between group">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-medium text-gray-800 truncate group-hover:text-indigo-600 transition-colors">
                                            {{ $stock->product->name }}
                                        </p>
                                        @if($stock->quantity <= 0)
                                            <span class="flex-shrink-0 inline-block px-1.5 py-0.5 text-[9px] font-black bg-red-600 text-white rounded animate-pulse">
                                                AGOTADO
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400">
                                        {{ $stock->warehouse->name }}
                                    </p>
                                </div>
                                
                                <div class="flex flex-col items-end ml-4">
                                    <span class="text-sm font-bold {{ $stock->quantity <= 0 ? 'text-red-600' : 'text-orange-500' }}">
                                        {{ number_format($stock->quantity, 0) }}
                                    </span>
                                    <p class="text-[10px] text-gray-400 font-medium italic">Min: {{ number_format($stock->min_stock, 0) }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <x-heroicon-o-check-badge class="w-10 h-10 text-green-400 mx-auto mb-2"/>
                                <p class="text-xs text-gray-500">Todo el stock está niveles normales</p>
                            </div>
                        @endforelse
                    </div>

                    @if($lowStockProducts->count() > 0)
                        <div class="mt-5 pt-4 border-t border-gray-50">
                            <button @click="$dispatch('open-modal', 'register-production')" 
                                    class="w-full flex items-center justify-center gap-2 text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition">
                                <x-heroicon-s-plus-circle class="w-4 h-4"/>
                                Reponer inventario ahora
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('inventory.dashboard.charts')

    
    @include('inventory.dashboard.modals.production')
    @include('inventory.dashboard.modals.transfer')
</x-app-layout>