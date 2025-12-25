<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients\BusinessType;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BusinessTypeController extends Controller
{
    use SoftDeletesTrait;

    /**
     * Muestra el listado de tipos de negocios con filtros y paginación
     */
    public function index(Request $request) {
        // Obtener parámetros de búsqueda y filtro de estado de la solicitud
        $search = $request->query('search'); 
        $estadoFiltro = $request->query('estado'); // activo | inactivo | null 

        // Construir query con filtros dinámicos, ordenar por nombre y paginar
        $tipoNegocio = BusinessType::query()
            ->when($search, fn ($q) =>
                $q->where('nombre', 'like', "%{$search}%")
            )
            ->filtrarPorEstado($estadoFiltro)
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();


        // Retornar vista con estados y parámetros de filtrado
        return view('clients.negocios.index', compact('tipoNegocio', 'search', 'estadoFiltro'));
    }


    /**
     * Crear Tipos de Negocio
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:business_types,nombre',
        ]);


        $negocio = BusinessType::create([
            'nombre' => $request->nombre,
            'activo' => true,
        ]);


        // ... (redirección)
        return redirect()
            ->route('clients.negocios.index')
            ->with('success', 'Tipo de negocio "' . $negocio->nombre . '" creado exitosamente.');
    }


    /**
     * Actualizar datos
     */
    public function update(Request $request, BusinessType $negocio) {
        $request->validate([
            'nombre' => 'required|string|' . Rule::unique('business_types')->ignore($negocio->id),
        ]);

        $data = ['nombre' => $request->nombre];

        $negocio->update($data);


        // ... (redirección)
        return redirect()
            ->route('clients.negocios.index')
            ->with('success', 'Tipo de negocio "' . $negocio->nombre . '" actualizado exitosamente.');
    }

    public function toggleEstado(BusinessType $negocio)
    {
        if ($negocio->activo) {
            $activosCount = BusinessType::activos()->count();
            
            if ($activosCount <= 2) {
                return redirect()
                    ->route('clients.negocios.index')
                    ->with('error', 'No se puede desactivar. Deben existir al menos 2 estados activos en el catálogo.');
            }
        }
        $negocio->toggleActivo();

        return redirect()
            ->route('clients.negocios.index')
            ->with('success', 'Estado actualizado para "' . $negocio->nombre . '".');
    }


    // Elimina la BusinessType si no tiene relaciones (o desactiva la eliminación por defecto).
    public function destroy(BusinessType $negocio)
    {
        return $this->destroyTrait($negocio, null);
    }

    // Métodos abstractos que el trait necesita
    protected function getModelClass(): string { return \App\Models\Clients\BusinessType::class; }
    protected function getViewFolder(): string { return 'clients.negocios'; }
    protected function getRouteIndex(): string { return 'clients.negocios.index'; }
    protected function getRouteEliminadas(): string { return 'clients.negocios.eliminados'; }
    protected function getEntityName(): string { return 'Tipo de Negocio'; }
}
