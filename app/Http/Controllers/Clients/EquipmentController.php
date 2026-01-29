<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients\Equipment;
use App\Traits\SoftDeletesTrait;
use App\Filters\Equipment\EquipmentFilters;
use App\Services\Equipment\EquipmentCatalogService;
use App\Services\Equipment\EquipmentService;
use App\Tables\EquipmentTable;
use App\Http\Requests\Equipment\{
    StoreEquipmentRequest,
    UpdateEquipmentRequest,
    BulkEquipmentRequest
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Equipment\EquipmentsExport;

class EquipmentController extends Controller
{
    use SoftDeletesTrait;

    /**
     * Listado principal
     */
    public function index(Request $request, EquipmentCatalogService $catalogService)
    {
        $visibleColumns = $request->input('columns', EquipmentTable::defaultDesktop());
        $perPage = $request->input('per_page', 10);

        $equipments = (new EquipmentFilters($request))
            ->apply(Equipment::query()->withIndexRelations())
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('clients.equipment.partials.table', [
                'equipments'     => $equipments,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => EquipmentTable::allColumns(),
                'defaultDesktop' => EquipmentTable::defaultDesktop(),
                'defaultMobile'  => EquipmentTable::defaultMobile(),
                'bulkActions'    => true,
            ])->render();
        }

        return view('clients.equipment.index', array_merge(
            [
                'equipments'     => $equipments,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => EquipmentTable::allColumns(),
                'defaultDesktop' => EquipmentTable::defaultDesktop(),
                'defaultMobile'  => EquipmentTable::defaultMobile(),
                'bulkActions'    => true,
            ],
            $catalogService->getForFilters()
        ));
    }

    /**
     * Acciones masivas
     */
    public function bulk(BulkEquipmentRequest $request, EquipmentService $service)
    {
        try {
            $count = $service->performBulkAction(
                $request->ids,
                $request->action,
                $request->value
            );

            $label = $service->getActionLabel($request->action);
            $message = "Se han {$label} correctamente {$count} equipos.";

            session()->flash('success', $message);

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            Log::error("Error bulk Equipment: {$e->getMessage()}");

            return response()->json([
                'success' => false,
                'message' => 'No se pudo completar la operación.'
            ], 422);
        }
    }

    public function export(Request $request)
    {
        $query = (new EquipmentFilters($request))
            ->apply(Equipment::query());

        $fileName = 'equipos-' . now()->format('d-m-Y-H-i') . '.xlsx';

        return Excel::download(
            new EquipmentsExport($query),
            $fileName
        );
    }

    public function create(EquipmentCatalogService $catalogService)
    {
        return view('clients.equipment.create', $catalogService->getForForm());
    }

    public function store(StoreEquipmentRequest $request, EquipmentService $service)
    {
        $equipment = $service->create($request->validated());

        return redirect()
            ->route('clients.equipment.index')
            ->with('success', "Equipo {$equipment->code} creado correctamente.");
    }

    public function edit(Equipment $equipment, EquipmentCatalogService $catalogService)
    {
        return view('clients.equipment.edit', array_merge(
            ['equipment' => $equipment],
            $catalogService->getForForm()
        ));
    }

    public function update(UpdateEquipmentRequest $request, Equipment $equipment, EquipmentService $service)
    {
        $service->update($equipment, $request->validated());

        return redirect()
            ->route('clients.equipment.index')
            ->with('success', "Equipo {$equipment->code} actualizado correctamente.");
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        return $this->destroyTrait($equipment);
    }


    /* ===== Configuración del Trait ===== */
    protected function getModelClass(): string { return Equipment::class; }
    protected function getViewFolder(): string { return 'clients.equipment'; }
    protected function getRouteIndex(): string { return 'clients.equipment.index'; }
    protected function getRouteEliminadas(): string { return 'clients.equipment.eliminados'; }
    protected function getEntityName(): string { return 'Equipo'; }
}
