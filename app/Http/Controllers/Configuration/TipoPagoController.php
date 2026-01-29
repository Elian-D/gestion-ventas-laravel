<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Models\Configuration\TipoPago;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;

class TipoPagoController extends Controller
{
     use SoftDeletesTrait;
    /**
     * Muestra el listado de tipos de pagos con filtros y paginación
     */
    public function index(Request $request) {
        // Obtener parámetros de búsqueda y filtro de estado de la solicitud
        $search = $request->query('search'); 
        $estado = $request->query('estado'); 

        // Construir query con filtros dinámicos, ordenar por nombre y paginar
        $tipoPago = TipoPago::query()
            ->when($search, fn($q) => $q->where('nombre', 'like', "%{$search}%"))
            ->when($estado === 'activo', fn($q) => $q->activo())
            ->when($estado === 'inactivo', fn($q) => $q->inactivo())
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        // Retornar vista con documentos y parámetros de filtrado
        return view('configuration.pagos.index', compact('tipoPago', 'search', 'estado'));
    }

    /**
     * Almacena un nuevo Tipo de pago en la base de datos.
     * El estado se establece por defecto en ACTIVO (true).
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos
        $request->validate([
            'nombre' => 'required|string|unique:tipo_pagos,nombre'
        ]);

        // 2. Creación del registro
        $pago = TipoPago::create([
            'nombre' => $request->nombre,
            'estado' => true // Regla de negocio: por defecto, es activo.
        ]);

        // 3. Redirección (Necesario en CRUDs)
        return redirect()
            ->route('configuration.pagos.index')
            ->with('success', 'Tipo de pago "' . $pago->nombre . '" creado exitosamente.');
    }

    /**
     * Actualiza el TipoPago especificado en la base de datos.
     * Permite cambiar el nombre y el estado (activar/desactivar).
     */
    public function update(Request $request, TipoPago $tipoPago)
    {
        // 1. Validación de los datos
        $request->validate([
            'nombre' => ['required','string','unique:tipo_pagos,nombre,' . $tipoPago->id,],
        ]);
        
        // 2. Preparación de los datos
        $data = ['nombre' => $request->nombre,];
        
        // 3. Actualización del registro
        $tipoPago->update($data);

        // 4. Redirección y mensaje de éxito
        return redirect()
            ->route('configuration.pagos.index')
            ->with('success', 'Tipo de pago "' . $tipoPago->nombre . '" actualizado exitosamente.');
    }

    public function toggleEstado(TipoPago $tipoPago) {
    $tipoPago->toggleEstado();

    return redirect()
        ->route('configuration.pagos.index')
        ->with(
            'success',
            'Estado actualizado para "' . $tipoPago->nombre . '".'
        );
    }


    // Elimina el Tipo Pago si no tiene relaciones (o desactiva la eliminación por defecto).
    public function destroy(TipoPago $tipoPago)
    {
        return $this->destroyTrait($tipoPago, null);
    }

    // Métodos abstractos que el trait necesita
    protected function getModelClass(): string { return \App\Models\Configuration\TipoPago::class; }
    protected function getViewFolder(): string { return 'configuration.pagos'; }
    protected function getRouteIndex(): string { return 'configuration.pagos.index'; }
    protected function getRouteEliminadas(): string { return 'configuration.pagos.eliminados'; }
    protected function getEntityName(): string { return 'Tipo Pago'; }
}
