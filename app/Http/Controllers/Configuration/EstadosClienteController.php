<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Configuration\EstadosCliente;
use App\Http\Controllers\Controller;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EstadosClienteController extends Controller
{

    use SoftDeletesTrait;

    // Lista de colores permitidos para la validación y construcción de clases
    protected array $allowedColors = ['green', 'indigo', 'red', 'yellow', 'gray', 'blue', 'purple']; // Puedes agregar más

    /**
     * Función auxiliar para construir las clases de Tailwind
     */
    protected function buildStyleClasses(string $colorBase): array
    {
        return [
            'clase_fondo' => "bg-{$colorBase}-100",
            'clase_texto' => "text-{$colorBase}-800",
        ];
    }
    
    /**
     * Muestra el listado de estados de clientes con filtros y paginación
     */
    public function index(Request $request) {
        // Obtener parámetros de búsqueda y filtro de estado de la solicitud
        $search = $request->query('search'); 
        $estadoFiltro = $request->query('estado'); // activo | inactivo | null 

        // Construir query con filtros dinámicos, ordenar por nombre y paginar
        $estados = EstadosCliente::query()
            ->when($search, fn ($q) =>
                $q->where('nombre', 'like', "%{$search}%")
            )
            ->filtrarPorEstado($estadoFiltro)
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();


        // Retornar vista con estados y parámetros de filtrado
        return view('configuration.estados.index', compact('estados', 'search', 'estadoFiltro'));
    }


/**
     * Crear Estados de Cliente
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:estados_clientes,nombre',
            'color_base' => ['required', 'string', Rule::in($this->allowedColors)],
            'permite_operar' => 'required|boolean',
            'permite_facturar' => 'required|boolean',
        ]);


        $styleClasses = $this->buildStyleClasses($request->color_base);

        $estado = EstadosCliente::create(array_merge([
            'nombre' => $request->nombre,
            'activo' => true,
            'permite_operar' => $request->boolean('permite_operar'),
            'permite_facturar' => $request->boolean('permite_facturar'),
        ], $styleClasses));


        // ... (redirección)
        return redirect()
            ->route('configuration.estados.index')
            ->with('success', 'Estado de cliente "' . $estado->nombre . '" creado exitosamente.');
    }


    /**
     * Actualizar datos
     */
    public function update(Request $request, EstadosCliente $estado) {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                Rule::unique('estados_clientes')->ignore($estado->id),
            ],
            'color_base' => ['required', 'string', Rule::in($this->allowedColors)],
            'permite_operar' => 'required|boolean',
            'permite_facturar' => 'required|boolean',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'permite_operar' => $request->boolean('permite_operar'),
            'permite_facturar' => $request->boolean('permite_facturar'),
        ];

        $styleClasses = $this->buildStyleClasses($request->color_base);
        $data = array_merge($data, $styleClasses);

        $estado->update($data);


        // ... (redirección)
        return redirect()
            ->route('configuration.estados.index')
            ->with('success', 'Estado de cliente "' . $estado->nombre . '" actualizado exitosamente.');
    }

    public function toggleEstado(EstadosCliente $estado)
    {
        if ($estado->activo) {
            $activosCount = EstadosCliente::activos()->count();
            
            if ($activosCount <= 2) {
                return redirect()
                    ->route('configuration.estados.index')
                    ->with('error', 'No se puede desactivar. Deben existir al menos 2 estados activos en el catálogo.');
            }
        }
        $estado->toggleActivo();

        return redirect()
            ->route('configuration.estados.index')
            ->with('success', 'Estado actualizado para "' . $estado->nombre . '".');
    }


    // Elimina la EstadosCliente si no tiene relaciones (o desactiva la eliminación por defecto).
    public function destroy(EstadosCliente $estado)
    {
        return $this->destroyTrait($estado, null);
    }

    // Métodos abstractos que el trait necesita
    protected function getModelClass(): string { return \App\Models\Configuration\EstadosCliente::class; }
    protected function getViewFolder(): string { return 'configuration.estados'; }
    protected function getRouteIndex(): string { return 'configuration.estados.index'; }
    protected function getRouteEliminadas(): string { return 'configuration.estados.eliminados'; }
    protected function getEntityName(): string { return 'Estado de Cliente'; }
}
