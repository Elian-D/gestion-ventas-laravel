<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Configuration\EstadosCliente;
use App\Http\Controllers\Controller;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Configuration\ClientStateCategory;

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
    


    public function index(Request $request)
    {
        $search = $request->query('search');
        $estadoFiltro = $request->query('estado');

        $estados = EstadosCliente::query()
            ->with('categoria') // 🔥 eager loading
            ->when($search, fn ($q) =>
                $q->where('nombre', 'like', "%{$search}%")
            )
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        // ✅ contar una sola vez
        $activosCount = EstadosCliente::activos()->count();

        // categorías para los modales
        $categorias = ClientStateCategory::orderBy('name')->get();

        return view(
            'configuration.estados.index',
            compact('estados', 'search', 'estadoFiltro', 'activosCount', 'categorias')
        );
    }



/**
     * Crear Estados de Cliente
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:estados_clientes,nombre',
            'client_state_category_id' => 'required|exists:client_state_categories,id',
            'color_base' => ['required', 'string', Rule::in($this->allowedColors)],
        ]);


        $styleClasses = $this->buildStyleClasses($request->color_base);

        $estado = EstadosCliente::create(array_merge([
            'nombre' => $request->nombre,
            'client_state_category_id' => $request->client_state_category_id,
            'activo' => true,
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
            'client_state_category_id' => 'required|exists:client_state_categories,id',
            'color_base' => ['required', 'string', Rule::in($this->allowedColors)],
        ]);


        $estado->update(array_merge([
            'nombre' => $request->nombre,
            'client_state_category_id' => $request->client_state_category_id,
        ], $this->buildStyleClasses($request->color_base)));


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
