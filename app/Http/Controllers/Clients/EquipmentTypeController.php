<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients\EquipmentType;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EquipmentTypeController extends Controller
{
    use SoftDeletesTrait;

    /**
     * Muestra el listado de tipos de equipos con filtros y paginación
     */
    public function index(Request $request) {
        // Obtener parámetros de búsqueda y filtro de estado de la solicitud
        $search = $request->query('search'); 
        $estadoFiltro = $request->query('estado'); // activo | inactivo | null 

        // Construir query con filtros dinámicos, ordenar por nombre y paginar
        $tipoEquipo = EquipmentType::query()
            ->when($search, fn ($q) =>
                $q->where('nombre', 'like', "%{$search}%")
            )
            ->filtrarPorEstado($estadoFiltro)
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();


        // Retornar vista con estados y parámetros de filtrado
        return view('clients.equipos.index', compact('tipoEquipo', 'search', 'estadoFiltro'));
    }


    /**
     * Crear Tipos de Negocio
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:equipment_types,nombre',
        ]);


        $equipo = EquipmentType::create([
            'nombre' => $request->nombre,
            'activo' => true,
        ]);


        // ... (redirección)
        return redirect()
            ->route('clients.equipos.index')
            ->with('success', 'Tipo de equipo "' . $equipo->nombre . '" creado exitosamente.');
    }


    /**
     * Actualizar datos
     */
    public function update(Request $request, EquipmentType $equipo) {
        $request->validate([
            'nombre' => 'required|string|' . Rule::unique('equipment_types')->ignore($equipo->id),
        ]);

        $data = ['nombre' => $request->nombre];

        $equipo->update($data);


        // ... (redirección)
        return redirect()
            ->route('clients.equipos.index')
            ->with('success', 'Tipo de equipo "' . $equipo->nombre . '" actualizado exitosamente.');
    }

    public function toggleEstado(EquipmentType $equipo)
    {
        if ($equipo->activo) {
            $activosCount = EquipmentType::activos()->count();
            
            if ($activosCount <= 2) {
                return redirect()
                    ->route('clients.equipos.index')
                    ->with('error', 'No se puede desactivar. Deben existir al menos 2 estados activos en el catálogo.');
            }
        }
        $equipo->toggleActivo();

        return redirect()
            ->route('clients.equipos.index')
            ->with('success', 'Estado actualizado para "' . $equipo->nombre . '".');
    }


    // Elimina la EquipmentType si no tiene relaciones (o desactiva la eliminación por defecto).
    public function destroy(EquipmentType $equipo)
    {
        return $this->destroyTrait($equipo, null);
    }

    // Métodos abstractos que el trait necesita
    protected function getModelClass(): string { return \App\Models\Clients\EquipmentType::class; }
    protected function getViewFolder(): string { return 'clients.equipos'; }
    protected function getRouteIndex(): string { return 'clients.equipos.index'; }
    protected function getRouteEliminadas(): string { return 'clients.equipos.eliminados'; }
    protected function getEntityName(): string { return 'Tipo de Equipo'; }
}
