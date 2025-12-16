<?php

namespace App\Http\Controllers\Geography;

use App\Http\Controllers\Controller;
use App\Models\Geography\Municipio;
use App\Models\Geography\Sector;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SectorController extends Controller
{
    use SoftDeletesTrait;
    /**
     * Muestra el listado de Sectores
     */
    public function index(Request $request)
    {
        $search = $request->query('search'); 
        $estado = $request->query('estado'); 
        $municipio_id = $request->query('municipio_id');

        // Validar que el Municipio exista y esté activa
        if ($municipio_id && ! Municipio::activo()->where('id', $municipio_id)->exists()) {
            $municipio_id = null;
        }

        $sectores = Sector::query()
            ->when($search, fn($q) => $q->where('nombre', 'like', "%{$search}%"))
            ->when($estado === 'activo', fn($q) => $q->activo())
            ->when($estado === 'inactivo', fn($q) => $q->inactivo())
            ->when($municipio_id && $municipio_id !== 'todos', fn($q) => $q->where('municipio_id', $municipio_id))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        // Aquí cargamos los Municipios activas para el select del filtro
        $municipios = Municipio::activo()->orderBy('nombre')->get();

        return view('geography.sectores.index', compact('sectores', 'search', 'estado', 'municipio_id', 'municipios'));
    }

    /**
     * Mostrar formulario
     */
    public function create()
    {   
        $municipios = Municipio::activo()->orderBy('nombre')->get();
        return view('geography.sectores.create', compact('municipios'));
    }

    /**
     * Crear Sector
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                Rule::unique('sectores')
                ->where(fn($query) => $query->where('municipio_id', $request->municipio_id)),
            ],

            'municipio_id' => [
                'required',
                Rule::exists('municipios', 'id')->where(fn($q) => $q->where('estado', true)),
            ]

        ]);

        $municipio = Municipio::find($request->municipio_id);
        $sector = $municipio->sectores()->create([
            'nombre' => $request->nombre,
            'estado' => true,
        ]);


        return redirect()
            ->route('geography.sectores.index')
            ->with('success', 'Sector "' . $sector->nombre . '" creado exitosamente.');
    }

    /**
     * Vista de edicion
     */
    public function edit(Sector $sector)
    {
        $municipios = Municipio::activo()->orderBy('nombre')->get();
        return view('geography.sectores.edit', compact('sector','municipios'));
    }

    /**
     * Actualizar datos
     */
    public function update(Request $request, Sector $sector) {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                Rule::unique('sectores')
                    ->ignore($sector->id)
                    ->where(fn($query) => $query->where('municipio_id', $request->municipio_id))

            ],
            'municipio_id' => [
                'required',
                Rule::exists('municipios', 'id')->where(fn($q) => $q->where('estado', true)),
            ]
        ]);


        // 2. Preparación de los datos
        $data = [
            'nombre' => $request->nombre,
            'municipio_id' => $request->municipio_id,
        ];
        
        $sector->update($data);


        // 4. Redirección y mensaje de éxito
        return redirect()
            ->route('geography.sectores.index')
            ->with('success', 'Sector "' . $sector->nombre . '" actualizado exitosamente.');
    }

    public function toggleEstado(Sector $sector) {
        $sector->toggleEstado();

        return redirect()
            ->route('geography.sectores.index')
            ->with(
                'success',
                'Estado actualizado para "' . $sector->nombre . '".'
            );
    }

    // Elimina el Municipio si no tiene relaciones (o desactiva la eliminación por defecto).
    public function destroy(Sector $sector)
    {
        return $this->destroyTrait($sector, null);
    }

    // Métodos abstractos que el trait necesita
    protected function getModelClass(): string { return \App\Models\Geography\Sector::class; }
    protected function getViewFolder(): string { return 'geography.sectores'; }
    protected function getRouteIndex(): string { return 'geography.sectores.index'; }
    protected function getRouteEliminadas(): string { return 'geography.sectores.eliminadas'; }
    protected function getEntityName(): string { return 'Sector'; }
}
