<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients\PointOfSale;
use App\Traits\SoftDeletesTrait;
use App\Filters\PointOfSale\PointOfSaleFilters;
use App\Services\PointOfSale\POSCatalogService;
use App\Services\PointOfSale\POSService;
use App\Tables\PointOfSaleTable;
use App\Http\Requests\PointOfSale\BulkPointOfSaleRequest;
use App\Http\Requests\PointOfSale\StorePointOfSaleRequest;
use App\Http\Requests\PointOfSale\UpdatePointOfSaleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PointOfSaleController extends Controller
{
    use SoftDeletesTrait;

    /**
     * Listado principal con Pipeline de Filtros y AJAX
     */
    public function index(Request $request, POSCatalogService $catalogService)
    {
        // 1. Configuración de columnas visibles
        $visibleColumns = $request->input('columns', PointOfSaleTable::defaultDesktop());
        $perPage = $request->input('per_page', 10);

        // 2. Aplicación de filtros mediante el Pipeline
        $pos = (new PointOfSaleFilters($request))
            ->apply(PointOfSale::query()->withIndexRelations())
            ->paginate($perPage)
            ->withQueryString();

        // 3. Respuesta para peticiones AJAX (DataTable)
        if ($request->ajax()) {
            return view('pos.partials.table', [
                'pos'            => $pos,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => PointOfSaleTable::allColumns(),
                'bulkActions'    => true,
            ])->render();
        }

        // 4. Carga de la vista completa
        return view('clients.pos.index', array_merge(
            [
                'pos'            => $pos,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => PointOfSaleTable::allColumns(),
                'defaultDesktop' => PointOfSaleTable::defaultDesktop(),
                'defaultMobile'  => PointOfSaleTable::defaultMobile(),
                'bulkActions'    => true,
            ],
            $catalogService->getForFilters() // Inyecta clients, businessTypes y states
        ));
    }

    /**
     * Acciones masivas (Activar/Desactivar, Cambiar tipo, etc.)
     */
    public function bulk(BulkPointOfSaleRequest $request, POSService $posService)
    {
        try {
            $count = $posService->performBulkAction(
                $request->ids, 
                $request->action, 
                $request->value
            );

            $label = $posService->getActionLabel($request->action);
            $message = "Se han {$label} correctamente {$count} puntos de venta.";

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error("Error en bulk action POS: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Error al procesar la acción masiva.'
            ], 422);
        }
    }

    public function create(POSCatalogService $catalogService)
    {
        return view('clients.pos.create', $catalogService->getForForm());
    }

    public function store(StorePointOfSaleRequest $request, POSService $posService)
    {
        $pos = $posService->createPOS($request->validated());
        return redirect()->route('clients.pos.index')
            ->with('success', "Punto de venta {$pos->name} ({$pos->code}) creado.");
    }

    public function edit(PointOfSale $pos, POSCatalogService $catalogService)
    {
        return view('clients.pos.edit', array_merge(
            ['pos' => $pos],
            $catalogService->getForForm()
        ));
    }

    public function update(UpdatePointOfSaleRequest $request, PointOfSale $pos, POSService $posService)
    {
        $posService->updatePOS($pos, $request->validated());
        return redirect()->route('clients.pos.index')
            ->with('success', "Punto de venta {$pos->name} actualizado correctamente.");
    }

    public function destroy(PointOfSale $pos)
    {
        return $this->destroyTrait($pos);
    }

    /* Configuración del Trait para la papelera */
    protected function getModelClass(): string { return PointOfSale::class; }
    protected function getViewFolder(): string { return 'clients.pos'; }
    protected function getRouteIndex(): string { return 'clients.pos.index'; }
    protected function getRouteEliminadas(): string { return 'clients.pos.eliminados'; }
    protected function getEntityName(): string { return 'Punto de Venta'; }
}