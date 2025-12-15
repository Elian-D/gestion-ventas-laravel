<?php

namespace App\Http\Controllers\Geography;

use App\Http\Controllers\Controller;
use App\Models\Provincia;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProvinciaController extends Controller {

    use SoftDeletesTrait;
    
    /**
     * Muestra el listado provincias
     */
    public function index(Request $request) {
        // Obtener parámetros de búsqueda y filtro de estado de la solicitud
        $search = $request->query('search'); 
        $estado = $request->query('estado'); 

        // Construir query con filtros dinámicos, ordenar por nombre y paginar
        $provincias = Provincia::query()
            ->when($search, fn($q) => $q->where('nombre', 'like', "%{$search}%"))
            ->when($estado === 'activo', fn($q) => $q->activo())
            ->when($estado === 'inactivo', fn($q) => $q->inactivo())
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        // Retornar vista con provincias y parámetros de filtrado
        return view('provincias.index', compact('provincias', 'search', 'estado'));
    }


    /**
     * Mostrar formulario
     */
    public function create()
    {
        return view('provincias.create');
    }

    /**
     * Crear provincia
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:provincias,nombre'
        ]);

        $provincia = Provincia::create([
            'nombre' => $request->nombre,
            'estado' => true // Por defecto 'true'
        ]);

        //
        return redirect()
            ->route('provincias.index')
            ->with('success', 'Provincia "' . $provincia->nombre . '" creada exitosamente.');

    }

    /**
     * Vista de edicion
     */
    public function edit(Provincia $provincia)
    {
        return view('provincias.edit', compact('provincia'));
    }

    /**
     * Actualizar datos
     */
    public function update(Request $request, Provincia $provincia) {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                Rule::unique('provincias')->ignore($provincia->id),
            ],
        ]);


        // 2. Preparación de los datos
        $data = ['nombre' => $request->nombre,];
        
        // 3. Actualización del registro
        $provincia->update($data);

        // 4. Redirección y mensaje de éxito
        return redirect()
            ->route('provincias.index')
            ->with('success', 'Provincia "' . $provincia->nombre . '" actualizada exitosamente.');
    }

    public function toggleEstado(Provincia $provincia) {
        $provincia->toggleEstado();

        return redirect()
            ->route('provincias.index')
            ->with(
                'success',
                'Estado actualizado para "' . $provincia->nombre . '".'
            );
    }

    // Elimina la Provincia si no tiene relaciones (o desactiva la eliminación por defecto).
    public function destroy(Provincia $provincia)
    {
        return $this->destroyTrait($provincia, null);
    }

    // Métodos abstractos que el trait necesita
    protected function getModelClass(): string { return \App\Models\Provincia::class; }
    protected function getViewFolder(): string { return 'provincias'; }
    protected function getRouteIndex(): string { return 'provincias.index'; }
    protected function getRouteEliminadas(): string { return 'provincias.eliminadas'; }
    protected function getEntityName(): string { return 'Provincia'; }
}
