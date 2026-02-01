<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    {{-- No clicable --}}
    <x-dashboard.kpi-card 
        title="Productos Totales" 
        :value="$stats['total_products']" 
        icon="cube" 
        color="blue" 
    />

    <x-dashboard.kpi-card 
        title="Existencias" 
        :value="number_format($stats['total_stock'], 0)" 
        icon="archive-box" 
        color="green" 
        secondary-text="Unidades disponibles"
        href="{{ route('inventory.stocks.index') }}"
    />

    {{-- Clicable a una ruta de stock bajo --}}
    <x-dashboard.kpi-card 
        title="Stock Bajo" 
        :value="$stats['low_stock']" 
        icon="exclamation-triangle" 
        color="red" 
        :trend="$stats['low_stock'] > 0 ? 'Requiere atención' : 'Todo OK'"
        :trend-up="false"
    />

    {{-- Clicable a Almacenes --}}
    <x-dashboard.kpi-card 
        title="Almacenes Activos" 
        :value="$stats['active_warehouses']" 
        icon="building-office-2" 
        color="indigo" 
        href="{{ route('inventory.warehouses.index') }}"
    />
</div>