<?php

namespace App\Http\Controllers\Products;

use App\Filters\Units\UnitsFilters;
use App\Models\Products\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tables\UnitsTable;
use App\Traits\SoftDeletesTrait;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    use SoftDeletesTrait;

    public function index(Request $request)
    {
        $visibleColumns = $request->input('columns', UnitsTable::defaultDesktop());
        $perPage = $request->input('per_page', 10);

        $units = (new UnitsFilters($request))
            ->apply(Unit::query())
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('products.units.partials.table', [
                'units'   => $units,
                'visibleColumns'  => $visibleColumns,
                'allColumns'      => UnitsTable::allColumns(),
                'defaultDesktop'  => UnitsTable::defaultDesktop(),
                'defaultMobile'   => UnitsTable::defaultMobile(),
            ])->render();
        }

        return view('products.units.index', array_merge(
            [
                'units'  => $units,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => UnitsTable::allColumns(),
                'defaultDesktop' => UnitsTable::defaultDesktop(),
                'defaultMobile'  => UnitsTable::defaultMobile(),
            ],
        ));
    }

    /**
     * Crear Tipos de Negocio
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'abbreviation' => [
                'required',
                'string',
                Rule::unique('units', 'abbreviation'),
            ],
            'is_active' => ['required', 'boolean'],
        ]);

        $unit = Unit::create([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
            'is_active' => $request->is_active
        ]);


        // ... (redirección)
        return redirect()
            ->route('products.units.index')
            ->with('success', 'Unidad de medida "' . $unit->name . '" creada exitosamente.');
    }


    public function update(Request $request, Unit $unit) {
        $request->validate([
            'name' => ['required', 'string'],
            'abbreviation' => [
                'required',
                'string',
                Rule::unique('units', 'abbreviation')->ignore($unit->id),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ]);


        // Convertimos el input a boolean para comparar correctamente
        $nuevoEstado = $request->boolean('is_active');

        // REGLA: Si la Unidad de medida ESTABA activa y ahora la quieren DESACTIVAR
        if ($unit->is_active && !$nuevoEstado) {
            // Contamos cuántas Unidad de medidas activas hay en TOTAL en la base de datos
            $totalActivas = Unit::where('is_active', true)->count();

            if ($totalActivas <= 1) {
                return redirect()
                    ->route('products.units.index')
                    ->with('error', 'Acción denegada: El catálogo requiere al menos una unidad de medida activa para funcionar.');
            }
        }

        $unit->update([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
            'is_active' => $nuevoEstado, // Importante usar la variable ya procesada
        ]);

        return redirect()
            ->route('products.units.index')
            ->with('success', "Unidad de medida \"{$unit->name}\" actualizada correctamente.");
    }

    public function toggleEstado(Unit $unit)
    {
        if ($unit->is_active) {
            $totalActivas = Unit::where('is_active', true)->count();

            if ($totalActivas <= 1) {
                return redirect()
                    ->route('products.units.index')
                    ->with('error', 'Acción denegada: El catálogo requiere al menos una Unidad de medida activa para funcionar.');
            }
        }
        $unit->toggleActivo();

        return redirect()
            ->route('products.units.index')
            ->with('success', 'Estado actualizado para "' . $unit->name . '".');
    }


    // Elimina la Unit si no tiene relaciones (o desactiva la eliminación por defecto).
    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        return $this->destroyTrait($unit);
    }

    // Métodos abstractos que el trait necesita
    protected function getModelClass(): string { return \App\Models\Products\Unit::class; }
    protected function getViewFolder(): string { return 'products.units'; }
    protected function getRouteIndex(): string { return 'products.units.index'; }
    protected function getRouteEliminadas(): string { return 'products.units.eliminados'; }
    protected function getEntityName(): string { return 'Unidad de medida'; }
}
