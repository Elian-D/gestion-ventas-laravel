<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Impuesto;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;

class ImpuestoController extends Controller
{
     use SoftDeletesTrait;
    /**
     * Muestra el listado de los impuestos con filtros y paginación
     */
    public function index(Request $request) {
        // Obtener parámetros de búsqueda y filtro de estado de la solicitud
        $search = $request->query('search'); 
        $estado = $request->query('estado'); 

        // Construir query con filtros dinámicos, ordenar por nombre y paginar
        $impuesto = Impuesto::query()
            ->when($search, fn($q) => $q->where('nombre', 'like', "%{$search}%"))
            ->when($estado === 'activo', fn($q) => $q->activo())
            ->when($estado === 'inactivo', fn($q) => $q->inactivo())
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        // Retornar vista con documentos y parámetros de filtrado
        return view('configuration.impuestos.index', compact('impuesto', 'search', 'estado'));
    }

    /**
     * Almacena un nuevo Impuesto en la base de datos.
     * El estado se establece por defecto en ACTIVO (true).
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos
        $request->validate([
            'nombre' => 'required|string|unique:impuestos,nombre',
            'tipo' => 'required|in:' . Impuesto::TIPO_PORCENTAJE . ',' . Impuesto::TIPO_FIJO,
            'valor' => 'required|numeric|min:0|max:999999.99',
            'es_incluido' => 'nullable|boolean',
        ]);

        // 2. Creación del registro
        $impuesto = Impuesto::create([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'valor' => $request->valor,
            'es_incluido' => $request->boolean('es_incluido'),
            'estado' => true // Regla de negocio: por defecto, es activo.
        ]);

        // 3. Redirección (Necesario en CRUDs)
        return redirect()
            ->route('configuration.impuestos.index')
            ->with('success', 'Impuesto "' . $impuesto->nombre . '" creado exitosamente.');
    }

    /**
     * Actualiza el Impuesto especificado en la base de datos.
     * Permite cambiar el nombre y el estado (activar/desactivar).
     */
    public function update(Request $request, Impuesto $impuesto)
    {
        // 1. Validación de los datos
        $request->validate([
            'nombre' => 'required|string|unique:impuestos,nombre,' . $impuesto->id,
            'tipo' => 'required|in:' . Impuesto::TIPO_PORCENTAJE . ',' . Impuesto::TIPO_FIJO,
            'valor' => 'required|numeric|min:0|max:999999.99',
            'es_incluido' => 'nullable|boolean',
        ]);
        
        // 2. Preparación de los datos
        $data = [
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'valor' => $request->valor,
            'es_incluido' => $request->boolean('es_incluido'),
        ];
        
        // 3. Actualización del registro
        $impuesto->update($data);

        // 4. Redirección y mensaje de éxito
        return redirect()
            ->route('configuration.impuestos.index')
            ->with('success', 'Impuesto "' . $impuesto->nombre . '" actualizado exitosamente.');
    }

    public function toggleEstado(Impuesto $impuesto) 
    {
        $impuesto->toggleEstado();

        return redirect()
            ->route('configuration.impuestos.index')
            ->with(
                'success',
                'Estado actualizado para "' . $impuesto->nombre . '".'
            );
    }


    // Elimina el Impuesto si no tiene relaciones (o desactiva la eliminación por defecto).
    public function destroy(Impuesto $impuesto)
    {
        return $this->destroyTrait($impuesto, null);
    }

    // Métodos abstractos que el trait necesita
    protected function getModelClass(): string { return \App\Models\Configuration\Impuesto::class; }
    protected function getViewFolder(): string { return 'configuration.impuestos'; }
    protected function getRouteIndex(): string { return 'configuration.impuestos.index'; }
    protected function getRouteEliminadas(): string { return 'configuration.impuestos.eliminados'; }
    protected function getEntityName(): string { return 'Impuesto'; }
}
