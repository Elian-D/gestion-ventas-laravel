<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Models\Configuration\TipoDocumento;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    use SoftDeletesTrait;
    /**
     * Muestra el listado de documentos con filtros y paginación
     */
    public function index(Request $request) {
        // Obtener parámetros de búsqueda y filtro de estado de la solicitud
        $search = $request->query('search'); 
        $estado = $request->query('estado'); 

        // Construir query con filtros dinámicos, ordenar por nombre y paginar
        $tipoDocumento = TipoDocumento::query()
            ->when($search, fn($q) => $q->where('nombre', 'like', "%{$search}%"))
            ->when($estado === 'activo', fn($q) => $q->activo())
            ->when($estado === 'inactivo', fn($q) => $q->inactivo())
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        // Retornar vista con documentos y parámetros de filtrado
        return view('configuration.documentos.index', compact('tipoDocumento', 'search', 'estado'));
    }

    /**
     * Almacena un nuevo tipo de documento en la base de datos.
     * El estado se establece por defecto en ACTIVO (true).
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos
        $request->validate([
            'nombre' => 'required|string|unique:tipo_documentos,nombre'
        ]);

        // 2. Creación del registro
        $documento = TipoDocumento::create([
            'nombre' => $request->nombre,
            'estado' => true // Regla de negocio: por defecto, es activo.
        ]);

        // 3. Redirección (Necesario en CRUDs)
        return redirect()
            ->route('configuration.documentos.index')
            ->with('success', 'Tipo de documento "' . $documento->nombre . '" creado exitosamente.');
    }

    /**
     * Actualiza el TipoDocumento especificado en la base de datos.
     * Permite cambiar el nombre y el estado (activar/desactivar).
     */
    public function update(Request $request, TipoDocumento $tipoDocumento)
    {
        // 1. Validación de los datos
        $request->validate([
            'nombre' => ['required','string','unique:tipo_documentos,nombre,' . $tipoDocumento->id,],
        ]);
        
        // 2. Preparación de los datos
        $data = ['nombre' => $request->nombre,];
        
        // 3. Actualización del registro
        $tipoDocumento->update($data);

        // 4. Redirección y mensaje de éxito
        return redirect()
            ->route('configuration.documentos.index')
            ->with('success', 'Tipo de documento "' . $tipoDocumento->nombre . '" actualizado exitosamente.');
    }

    public function toggleEstado(TipoDocumento $tipoDocumento) {
    $tipoDocumento->toggleEstado();

    return redirect()
        ->route('configuration.documentos.index')
        ->with(
            'success',
            'Estado actualizado para "' . $tipoDocumento->nombre . '".'
        );
    }


    // Elimina el Tipo documento si no tiene relaciones (o desactiva la eliminación por defecto).
    public function destroy(TipoDocumento $tipoDocumento)
    {
        return $this->destroyTrait($tipoDocumento, null);
    }

    // Métodos abstractos que el trait necesita
    protected function getModelClass(): string { return \App\Models\Configuration\TipoDocumento::class; }
    protected function getViewFolder(): string { return 'configuration.documentos'; }
    protected function getRouteIndex(): string { return 'configuration.documentos.index'; }
    protected function getRouteEliminadas(): string { return 'configuration.documentos.eliminados'; }
    protected function getEntityName(): string { return 'Tipo Documento'; }
}
