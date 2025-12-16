<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Configuration\DiaSemana;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiaSemanaController extends Controller
{
    /**
     * Muestra el listado de documentos con filtros y paginación
     */
    public function index(Request $request) {
        // Obtener parámetros de búsqueda y filtro de estado de la solicitud
        $search = $request->query('search'); 
        $estado = $request->query('estado'); 

        // Construir query con filtros dinámicos, ordenar por nombre y paginar
        $diaSemana = DiaSemana::query()
            ->when($search, fn($q) => $q->where('nombre', 'like', "%{$search}%"))
            ->when($estado === 'activo', fn($q) => $q->activo())
            ->when($estado === 'inactivo', fn($q) => $q->inactivo())
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        // Retornar vista con documentos y parámetros de filtrado
        return view('configuration.dias.index', compact('diaSemana', 'search', 'estado'));
    }

    public function toggleEstado(DiaSemana $diaSemana) {
    $diaSemana->toggleEstado();

    return redirect()
        ->route('configuration.dias.index')
        ->with(
            'success',
            'Estado actualizado para "' . $diaSemana->nombre . '".'
        );
    }

}
