<?php

namespace App\Http\Controllers\Geography;

use App\Http\Controllers\Controller;
use App\Models\Geography\Municipio;
use App\Models\Geography\Provincia;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MunicipioController extends Controller
{
    use SoftDeletesTrait;
    /**
     * Muestra el listado de municios
     */
    public function index(Request $request)
    {
        $search = $request->query('search'); 
        $estado = $request->query('estado'); 
        $provincia_id = $request->query('provincia_id');

        // Validar que la provincia exista y esté activa
        if ($provincia_id && ! Provincia::activo()->where('id', $provincia_id)->exists()) {
            $provincia_id = null;
        }

        $municipios = Municipio::query()
            ->when($search, fn($q) => $q->where('nombre', 'like', "%{$search}%"))
            ->when($estado === 'activo', fn($q) => $q->activo())
            ->when($estado === 'inactivo', fn($q) => $q->inactivo())
            ->when($provincia_id && $provincia_id !== 'todos', fn($q) => $q->where('provincia_id', $provincia_id))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        // Aquí cargamos las provincias activas para el select del filtro
        $provincias = Provincia::activo()->orderBy('nombre')->get();

        return view('municipios.index', compact('municipios', 'search', 'estado', 'provincia_id', 'provincias'));
    }

    /**
     * Mostrar formulario
     */
    public function create()
    {   
        $provincias = Provincia::activo()->orderBy('nombre')->get();
        return view('municipios.create', compact('provincias'));
    }

    /**
     * Crear Municipio
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                Rule::unique('municipios')
                ->where(fn($query) => $query->where('provincia_id', $request->provincia_id)),
            ],

            'provincia_id' => [
                'required',
                Rule::exists('provincias', 'id')->where(fn($q) => $q->where('estado', true)),
            ]

        ]);

        $provincia = Provincia::find($request->provincia_id);
        $municipio = $provincia->municipios()->create([
            'nombre' => $request->nombre,
            'estado' => true,
        ]);


        return redirect()
            ->route('municipios.index')
            ->with('success', 'Municipio "' . $municipio->nombre . '" creado exitosamente.');
    }

    /**
     * Vista de edicion
     */
    public function edit(Municipio $municipio)
    {
        $provincias = Provincia::activo()->orderBy('nombre')->get();
        return view('municipios.edit', compact('municipio','provincias'));
    }

    /**
     * Actualizar datos
     */
    public function update(Request $request, Municipio $municipio) {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                Rule::unique('municipios')
                    ->ignore($municipio->id)
                    ->where(fn($query) => $query->where('provincia_id', $request->provincia_id))

            ],
            'provincia_id' => [
                'required',
                Rule::exists('provincias', 'id')->where(fn($q) => $q->where('estado', true)),
            ]
        ]);


        // 2. Preparación de los datos
        $data = [
            'nombre' => $request->nombre,
            'provincia_id' => $request->provincia_id,
        ];
        
        $municipio->update($data);


        // 4. Redirección y mensaje de éxito
        return redirect()
            ->route('municipios.index')
            ->with('success', 'Municipio "' . $municipio->nombre . '" actualizado exitosamente.');
    }

    public function toggleEstado(Municipio $municipio) {
        $municipio->toggleEstado();

        return redirect()
            ->route('municipios.index')
            ->with(
                'success',
                'Estado actualizado para "' . $municipio->nombre . '".'
            );
    }

    // Elimina el Municipio si no tiene relaciones (o desactiva la eliminación por defecto).
    public function destroy(Municipio $municipio)
    {
        return $this->destroyTrait($municipio, null);
    }

    // Métodos abstractos que el trait necesita
    protected function getModelClass(): string { return \App\Models\Geography\Municipio::class; }
    protected function getViewFolder(): string { return 'municipios'; }
    protected function getRouteIndex(): string { return 'municipios.index'; }
    protected function getRouteEliminadas(): string { return 'municipios.eliminadas'; }
    protected function getEntityName(): string { return 'Municipio'; }
}
