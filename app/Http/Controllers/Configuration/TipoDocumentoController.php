<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
/**
     * Muestra el listado de tipos de documento y prepara la vista única.
     */
    public function index(Request $request)
    {
        
        $search = $request->query('search'); 
        $estado = $request->query('estado'); 

        // 2. Construir la consulta base
        $tipoDocumento = TipoDocumento::query()
            // El método query() inicializa el constructor de consultas de Eloquent para el modelo TipoDocumento.

            // 3. Aplicar filtro de búsqueda por nombre (si existe)
            ->when($search, function ($query, $search) {
                return $query->where('nombre', 'like', "%{$search}%");
            })
            // 4. Aplicar filtro por estado (si existe)
            ->when($estado !== null && $estado !== 'todos', function ($query) use ($estado) {
                $estadoBooleano = ($estado === 'activo'); 
                
                return $query->where('estado', $estadoBooleano);
            })
            // 5. Ordenar el resultado
            ->orderBy('nombre', 'asc') 
            // 6. Paginación y mantenimiento de Query Strings
            ->paginate(10) 
            ->withQueryString(); 

        // 7. Enviar datos a la vista
        return view('configuration.tipo_documentos', compact('tipoDocumento', 'search', 'estado'));
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
            ->route('tipos-documentos.index')
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
            ->route('tipos-documentos.index')
            ->with('success', 'Tipo de documento "' . $tipoDocumento->nombre . '" actualizado exitosamente.');
    }

    public function toggleEstado(TipoDocumento $tipoDocumento) {
    $tipoDocumento->toggleEstado();

    return redirect()
        ->route('tipos-documentos.index')
        ->with(
            'success',
            'Estado actualizado para "' . $tipoDocumento->nombre . '".'
        );
    }


    /**
     * Elimina el TipoDocumento si no tiene relaciones (o desactiva la eliminación por defecto).
     *
     * @param \App\Models\TipoDocumento $tipoDocumento
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TipoDocumento $tipoDocumento)
    {
        // ----------------------------------------------------------------------
        // 1. LÓGICA DE PROTECCIÓN (Preparado para Cliente)
        // ----------------------------------------------------------------------

        /*
        // TODO: Descomentar y ajustar cuando el modelo Cliente exista y tenga la relación.
        if ($tipoDocumento->clientes()->exists()) {
            // Si la relación con clientes existe, no permitimos la eliminación.
            return redirect()
                ->route('tipos-documentos.index')
                ->with('error', 'Clientes usando el documento "' . $tipoDocumento->nombre . '". No es posible eliminar. Por favor, desactive el documento.');
        }
        */
        
        // ----------------------------------------------------------------------
        // 2. ELIMINACIÓN FÍSICA
        // ----------------------------------------------------------------------

        // Si pasa la comprobación (o si está comentada como ahora), procedemos a borrar.
        try {
            $nombreEliminado = $tipoDocumento->nombre; // Guardamos el nombre antes de borrar para el mensaje
            $tipoDocumento->delete(); // Ejecuta la eliminación del registro en la DB

            // 3. Redirección y mensaje de éxito
            return redirect()
                ->route('tipos-documentos.index')
                ->with('success', 'Tipo de documento "' . $nombreEliminado . '" ha sido eliminado definitivamente.');

        } catch (\Exception $e) {
            // En caso de que falle la eliminación por cualquier otra razón (ej. restricción de clave foránea no detectada)
            return redirect()
                ->route('tipos-documentos.index')
                ->with('error', 'Error al intentar eliminar el documento "' . $tipoDocumento->nombre . '". Contacte a soporte.');
        }
    }
}
