<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-[1600px] mx-auto">
        
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Centro Contable</h2>
                <p class="text-sm text-gray-500 mt-1">Monitoreo de salud financiera y rentabilidad</p>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('accounting.journal_entries.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-indigo-700 transition">
                    <x-heroicon-s-document-plus class="w-5 h-5"/>
                    Nuevo Asiento Manual
                </a>
            </div>
        </div>

        {{-- KPIs: Ajustado a 3 columnas para que las 6 queden alineadas --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
            <x-dashboard.kpi-card 
                title="Efectivo Disponible" 
                :value="'$' . number_format($stats['cash_balance'], 2)" 
                icon="banknotes" color="green" 
            />

            <x-dashboard.kpi-card 
                title="Cuentas por Cobrar" 
                :value="'$' . number_format($stats['cxc_balance'], 2)" 
                icon="user-group" color="blue" secondary-text="Pendiente de cobro"
            />

            <x-dashboard.kpi-card 
                title="Patrimonio en Hielo" 
                :value="'$' . number_format($stats['inventory_value'], 2)" 
                icon="square-3-stack-3d" color="indigo" secondary-text="Valor en almacenes"
            />

            <x-dashboard.kpi-card 
                title="Utilidad Bruta" 
                :value="'$' . number_format($stats['gross_profit'], 2)" 
                icon="chart-bar" 
                color="{{ $stats['gross_profit'] >= 0 ? 'green' : 'red' }}" 
                :trend="number_format($stats['profit_margin'], 1) . '% Margen'"
            />

            <x-dashboard.kpi-card 
                title="Cuentas por Pagar" 
                :value="'$' . number_format($stats['cxp_balance'], 2)" 
                icon="credit-card" color="red" secondary-text="Deuda a proveedores"
            />

            <x-dashboard.kpi-card 
                title="Ratio de Liquidez" 
                :value="number_format($stats['liquidity_ratio'], 2)" 
                icon="scale" 
                color="{{ $stats['liquidity_ratio'] >= 1 ? 'green' : 'orange' }}" 
                secondary-text="{{ $stats['liquidity_ratio'] >= 1 ? 'Saludable' : 'Revisar flujo' }}"
            />
        </div>

        {{-- Gráficos --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
            <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Rendimiento Operativo</h3>
                <div id="chart-performance" class="min-h-[350px] w-full"></div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 mb-6 uppercase tracking-wider">Composición de Activos</h3>
                <div id="chart-composition" class="min-h-[300px] w-full"></div>
            </div>
        </div>

        {{-- Tabla de Movimientos Recientes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Últimos Movimientos Contables</h3>
                <span class="px-2.5 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full">Global</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 uppercase text-[11px] font-bold tracking-widest">
                            <th class="px-6 py-3">Fecha</th>
                            <th class="px-6 py-3">Referencia</th>
                            <th class="px-6 py-3">Descripción / Concepto</th>
                            <th class="px-6 py-3 text-right">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentEntries as $entry)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                {{ $entry->entry_date->format('d M, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                                    {{ $entry->reference }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ $entry->description }}
                                <div class="text-[11px] text-gray-400 font-normal">Registrado por {{ $entry->creator->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $entry->status === 'posted' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($entry->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Variables globales para las instancias de los gráficos
            let perfChart, compChart;

            setTimeout(() => {
                const chartsData = {
                    performance: {
                        labels: {!! json_encode($charts['performance']['labels']) !!},
                        income: {!! json_encode($charts['performance']['income']) !!},
                        costs: {!! json_encode($charts['performance']['costs']) !!}
                    },
                    composition: {
                        labels: {!! json_encode($charts['composition']['labels']) !!},
                        values: {!! json_encode($charts['composition']['values']) !!}
                    }
                };

                // 1. Gráfico de Rendimiento
                const perfEl = document.querySelector("#chart-performance");
                if (perfEl) {
                    perfChart = new ApexCharts(perfEl, {
                        series: [
                            { name: 'Ingresos', data: chartsData.performance.income.map(Number) },
                            { name: 'Costo de Ventas', data: chartsData.performance.costs.map(Number) }
                        ],
                        chart: { type: 'bar', height: 350, toolbar: { show: false }, redrawOnParentResize: true },
                        colors: ['#10B981', '#6366F1'],
                        xaxis: { categories: chartsData.performance.labels }
                    });
                    perfChart.render();
                }

                // 2. Gráfico de Composición
                const compEl = document.querySelector("#chart-composition");
                if (compEl) {
                    compChart = new ApexCharts(compEl, {
                        series: chartsData.composition.values.map(Number),
                        chart: { type: 'donut', height: 320, redrawOnParentResize: true },
                        labels: chartsData.composition.labels,
                        colors: ['#10B981', '#3B82F6', '#6366F1'],
                        legend: { position: 'bottom' }
                    });
                    compChart.render();
                }
            }, 150);

            // --- SOLUCIÓN AL DESBORDE ---
            // Escuchamos cambios en el sidebar. Como usas Alpine.js, 
            // lo más limpio es disparar un evento global cuando cambie 'isSidebarOpen'
            window.addEventListener('resize-charts', () => {
                if(perfChart) perfChart.windowResizeHandler();
                if(compChart) compChart.windowResizeHandler();
            });
        });
    </script>
    @endpush
</x-app-layout>