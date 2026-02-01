<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\InventoryMovement;
use App\Models\Inventory\InventoryStock;
use App\Models\Inventory\Warehouse;
use App\Models\Products\Product;
use Illuminate\Support\Facades\DB; // Importante para las consultas de gráficos

class InventoryDashboardController extends Controller
{
    public function __invoke()
    {
        // 1. Datos para gráfico de Flujo (Entradas vs Salidas - Últimos 7 días)
        $history = InventoryMovement::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw("SUM(CASE WHEN type = 'input' THEN quantity ELSE 0 END) as inputs"),
                DB::raw("SUM(CASE WHEN type = 'output' THEN ABS(quantity) ELSE 0 END) as outputs")
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 2. Datos para gráfico de Distribución (Stock por Almacén)
        $warehouseDist = Warehouse::withSum('stocks as total_qty', 'quantity')
            ->get()
            ->map(fn($wh) => [
                'name' => $wh->name,
                'qty'  => $wh->total_qty ?? 0
            ]);

        // 3. Productos con mayor rotación (Top 5 más movidos)
        $topProducts = InventoryMovement::select('product_id', DB::raw('SUM(ABS(quantity)) as total_moved'))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('total_moved')
            ->take(5)
            ->get();
        
        $lowStockCount = InventoryStock::whereColumn('quantity', '<=', 'min_stock')
            ->where('quantity', '>=', 0) 
            ->count();

        $lowStockProducts = InventoryStock::with(['product', 'warehouse'])
            ->whereColumn('quantity', '<=', 'min_stock')
            ->where('quantity', '>=', 0)
            ->orderBy('quantity', 'asc') // Los de 0 aparecerán primero
            ->take(5)
            ->get();

        return view('inventory.dashboard', [
            // KPIs
            'stats' => [
                'total_products' => Product::count(),
                'low_stock'      => InventoryStock::whereColumn('quantity', '<=', 'min_stock')->count(),
                'total_stock'    => InventoryStock::sum('quantity'),
                'active_warehouses' => Warehouse::count(),
                'low_stock' => $lowStockCount,
            ],
            
            // Datos para Gráficos
            'charts' => [
                'history' => [
                    'labels'  => $history->pluck('date'),
                    'inputs'  => $history->pluck('inputs'),
                    'outputs' => $history->pluck('outputs'),
                ],
                'distribution' => [
                    'labels' => $warehouseDist->pluck('name'),
                    'values' => $warehouseDist->pluck('qty'),
                ],
                'top_products' => [
                    'labels' => $topProducts->map(fn($tp) => $tp->product->name ?? 'N/A'),
                    'values' => $topProducts->pluck('total_moved'),
                ]
            ],

            // Listas y Catálogos
            'recentMovements' => InventoryMovement::with(['product', 'warehouse'])
                ->latest()
                ->take(5)
                ->get(),
            'warehouses' => Warehouse::all(),
            'products'   => Product::all(),
            'lowStockProducts' => $lowStockProducts,
            'types'      => InventoryMovement::getTypes(),
        ]);
    }
}