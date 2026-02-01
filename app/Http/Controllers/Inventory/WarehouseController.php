<?php

namespace App\Http\Controllers\Inventory;

use App\Filters\Warehouses\WarehousesFilters;
use App\Models\Inventory\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tables\WarehouseTable;
use App\Traits\SoftDeletesTrait;
use Illuminate\Validation\Rule;

class WarehouseController extends Controller
{
    use SoftDeletesTrait;

    public function index(Request $request)
    {
        $visibleColumns = $request->input('columns', WarehouseTable::defaultDesktop());
        $perPage = $request->input('per_page', 10);

        // Aplicamos los filtros (Search, Active y Type)
        $warehouses = (new WarehousesFilters($request))
            ->apply(Warehouse::query())
            ->paginate($perPage)
            ->withQueryString();

    
        $types = Warehouse::getTypes();
            
        if ($request->ajax()) {
            return view('inventory.warehouses.partials.table', [
                'warehouses'     => $warehouses,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => WarehouseTable::allColumns(),
                'defaultDesktop' => WarehouseTable::defaultDesktop(),
                'defaultMobile'  => WarehouseTable::defaultMobile(),
                'types'          => $types,
            ])->render();
        }

        return view('inventory.warehouses.index', [
            'warehouses'     => $warehouses,
            'visibleColumns' => $visibleColumns,
            'allColumns'     => WarehouseTable::allColumns(),
            'defaultDesktop' => WarehouseTable::defaultDesktop(),
            'defaultMobile'  => WarehouseTable::defaultMobile(),
            'types'          => $types,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'type'        => ['required', 'string', Rule::in(array_keys(Warehouse::getTypes()))],
            'address'     => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['required', 'boolean'],
        ]);

        $warehouse = Warehouse::create([
            'name'        => $request->name,
            'type'        => $request->type,
            'address'     => $request->address,
            'description' => $request->description,
            'is_active'   => $request->is_active,
            // El 'code' se genera abajo porque necesitamos el ID
        ]);

        // Generamos el código basado en el ID recién creado (Ej: BODEGA -> BOD-1)
        $warehouse->generateCode();

        return redirect()
            ->route('inventory.warehouses.index')
            ->with('success', "Almacén \"{$warehouse->name}\" creado con éxito (Código: {$warehouse->code}).");
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'type'        => ['required', 'string', Rule::in(array_keys(Warehouse::getTypes()))],
            'address'     => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $nuevoEstado = $request->boolean('is_active');

        // Protección: No desactivar si es el único almacén activo
        if ($warehouse->is_active && !$nuevoEstado) {
            if (Warehouse::where('is_active', true)->count() <= 1) {
                return redirect()
                    ->route('inventory.warehouses.index')
                    ->with('error', 'No se puede desactivar el único almacén activo del sistema.');
            }
        }

        // Guardamos el nombre viejo para comparar
        $nombreAnterior = $warehouse->name;

        $warehouse->update([
            'name'        => $request->name,
            'type'        => $request->type,
            'address'     => $request->address,
            'description' => $request->description,
            'is_active'   => $nuevoEstado,
        ]);

        // SI EL NOMBRE CAMBIÓ: Regeneramos el código (Ej: de ALM-1 a CEN-1 si cambió a "Central")
        if ($nombreAnterior !== $request->name) {
            $warehouse->generateCode();
        }

        return redirect()
            ->route('inventory.warehouses.index')
            ->with('success', "Almacén \"{$warehouse->name}\" actualizado correctamente.");
    }

    public function toggleEstado(Warehouse $warehouse)
    {
        if ($warehouse->is_active && Warehouse::where('is_active', true)->count() <= 1) {
            return redirect()
                ->back()
                ->with('error', 'Debe haber al menos un almacén activo.');
        }

        $warehouse->toggleActivo();

        return redirect()
            ->route('inventory.warehouses.index')
            ->with('success', 'Estado de "' . $warehouse->name . '" actualizado.');
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        // Aquí podrías añadir una validación extra: 
        // No borrar si tiene stock > 0 (esto lo haremos cuando tengamos la tabla de balances)
        return $this->destroyTrait($warehouse);
    }

    protected function getModelClass(): string { return Warehouse::class; }
    protected function getViewFolder(): string { return 'inventory.warehouses'; }
    protected function getRouteIndex(): string { return 'inventory.warehouses.index'; }
    protected function getRouteEliminadas(): string { return 'inventory.warehouses.eliminados'; }
    protected function getEntityName(): string { return 'Almacén'; }
}