<?php

namespace App\Http\Controllers\Inventory;

use App\Exports\Inventory\MovementsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreInventoryMovementRequest;
use App\Services\Inventory\InventoryMovementService;
use App\Services\Inventory\MovementCatalogService;
use App\Filters\Inventory\InventoryMovementFilters;
use App\Models\Inventory\InventoryMovement;
use App\Tables\InventoryMovementTable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InventoryMovementController extends Controller
{
    public function __construct(
        protected InventoryMovementService $service,
        protected MovementCatalogService $catalogService
    ) {}

    public function index(Request $request)
    {

        $visibleColumns = $request->input('columns', InventoryMovementTable::defaultDesktop());
        $perPage = $request->input('per_page', 15);

        // Aplicación del Pipeline de Filtros (Fecha, Almacén, Tipo, etc.)
        $movements = (new InventoryMovementFilters($request))
            ->apply(InventoryMovement::query()->withIndexRelations())
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('inventory.movements.partials.table', [
                'items'          => $movements,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => InventoryMovementTable::allColumns(),
            ])->render();
        }

        return view('inventory.movements.index', array_merge(
            [
                'items'          => $movements,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => InventoryMovementTable::allColumns(),
                'defaultDesktop' => InventoryMovementTable::defaultDesktop(),
                'defaultMobile'  => InventoryMovementTable::defaultMobile(),
            ],
            $this->catalogService->getForFilters()
        ));
    }

    /**
     * Registro de ajustes manuales (Subir/Bajar stock)
     */
    public function store(StoreInventoryMovementRequest $request)
    {
        $movement = $this->service->register($request->validated());

        return redirect()
            ->route('inventory.movements.index')
            ->with('success', "Ajuste #{$movement->id} realizado con éxito.");
    }

    /**
     * Exportación filtrada a Excel
     */
    public function export(Request $request)
    {
        $query = (new InventoryMovementFilters($request))
            ->apply(InventoryMovement::query());

        $fileName = 'movimientos-inventario-' . now()->format('d-m-Y-H-i') . '.xlsx';

        return Excel::download(new MovementsExport($query), $fileName);
    }
}