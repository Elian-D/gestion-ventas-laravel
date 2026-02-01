<?php

namespace App\Http\Controllers\Inventory;

use App\Exports\Inventory\InventoryStockExport;
use App\Http\Controllers\Controller;
use App\Models\Inventory\InventoryStock;
use App\Http\Requests\Inventory\UpdateInventoryStockRequest;
use App\Filters\Inventory\InventoryStockFilters\InventoryStockFilters;
use App\Services\Inventory\InventoryStockService\InventoryStockService;
use App\Services\Inventory\InventoryStockService\InventoryStockCatalogService;
use App\Tables\InventoryStockTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class InventoryStockController extends Controller
{
    protected $stockService;
    protected $catalogService;

    public function __construct(
        InventoryStockService $stockService, 
        InventoryStockCatalogService $catalogService
    ) {
        $this->stockService = $stockService;
        $this->catalogService = $catalogService;
    }

    /**
     * Dashboard de Stock Actual (Index)
     */
    public function index(Request $request)
    {
        // 1. Configuración de columnas
        $visibleColumns = $request->input('columns', InventoryStockTable::defaultDesktop());
        $perPage = $request->input('per_page', 15);

        // 2. Aplicación de filtros (Pipeline)
        // Cargamos relaciones para evitar N+1: producto, almacen, categoría y unidad
        $query = InventoryStock::with([
            'product.category', 
            'product.unit', 
            'warehouse'
        ]);

        $stocks = (new InventoryStockFilters($request))
            ->apply($query)
            ->paginate($perPage)
            ->withQueryString();

        // 3. Respuesta AJAX para recarga de tabla
        if ($request->ajax()) {
            return view('inventory.stocks.partials.table', [
                'stocks'         => $stocks,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => InventoryStockTable::allColumns(),
            ])->render();
        }

        // 4. Carga inicial de la vista
        return view('inventory.stocks.index', array_merge(
            [
                'stocks'         => $stocks,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => InventoryStockTable::allColumns(),
                'defaultDesktop' => InventoryStockTable::defaultDesktop(),
                'defaultMobile'  => InventoryStockTable::defaultMobile(),
            ],
            $this->catalogService->getForFilters()
        ));
    }

    /**
     * Actualizar solo el Stock Mínimo
     */
    public function updateMinStock(UpdateInventoryStockRequest $request, InventoryStock $stock)
    {
        try {
            $this->stockService->updateMinStock($stock, $request->min_stock);

            return response()->json([
                'success' => true,
                'message' => "Stock mínimo de {$stock->product->name} en {$stock->warehouse->name} actualizado."
            ]);

        } catch (\Exception $e) {
            Log::error("Error actualizando min_stock: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "No se pudo actualizar el stock mínimo."
            ], 422);
        }
    }

    /**
     * Exportación de inventario
     */
    public function export(Request $request)
    {
        // 1. Aplicamos los mismos filtros que en el index
        $query = (new InventoryStockFilters($request))
                    ->apply(InventoryStock::query());

        // 2. Definir nombre del archivo
        $fileName = 'inventario-actual-' . now()->format('d-m-Y-H-i') . '.xlsx';
        
        // 3. Ejecutar descarga
        return Excel::download(
            new InventoryStockExport($query), 
            $fileName
        );
    }
}