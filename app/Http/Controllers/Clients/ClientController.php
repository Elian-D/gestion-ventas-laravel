<?php

namespace App\Http\Controllers\Clients;

use App\Models\Clients\Client;
use App\Models\Clients\BusinessType;
use App\Models\Configuration\EstadosCliente;
use App\Models\Geo\State;
use App\Http\Controllers\Controller;
use App\Models\Configuration\ConfiguracionGeneral;
use App\Models\Configuration\TaxIdentifierType;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Filters\Client\ClientFilters;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    use SoftDeletesTrait;



    public function index(Request $request)
    {
        // Autorizamos que se pueden hacer bulk actions
        $bulkActions = true;

        $config = ConfiguracionGeneral::actual();
        $countryId = $config?->country_id;

        $states = $countryId 
            ? State::byCountry($countryId)->orderBy('name')->get() 
            : collect();

        $allColumns = [
            'id' => 'ID',
            'cliente' => 'Cliente',
            'ubicacion' => 'Ubicación',
            'estado_cliente' => 'Estado del Cliente',
            'estado_operativo' => 'Estado Operativo',
            'created_at' => 'Fecha Creación',
            'updated_at' => 'Última Actualización'
        ];

        $defaultVisible = ['id', 'cliente', 'ubicacion', 'estado_cliente', 'estado_operativo'];
        $visibleColumns = $request->input('columns', $defaultVisible);

        $perPage = $request->input('per_page', 10);

        $clients = (new ClientFilters($request))
            ->apply(Client::query())
            ->paginate($perPage)
            ->withQueryString();
        
        $estadosClientes = EstadosCliente::all();

        $tiposNegocio = BusinessType::all();

        if ($request->ajax()) {
            return view('clients.partials.table', compact(
                'clients',
                'allColumns',
                'visibleColumns',
                'defaultVisible',
                'bulkActions'
                ))->render();
        }

        return view('clients.index', compact(
        'clients', 
        'estadosClientes', 
        'tiposNegocio', 
        'states',
        'allColumns', 
        'visibleColumns', 
        'defaultVisible',
        'bulkActions'
    ));
    }

    /**
     * Acciones masivas
     */
    public function bulk(Request $request)
    {
        $allowedActions = ['activate', 'deactivate', 'delete', 'change_status', 'change_geo_state'];

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:clients,id',
            'action' => 'required|in:' . implode(',', $allowedActions),
            'value' => 'nullable'
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $value = $request->value;

        $count = count($ids);

        $actionLabel = match ($action) {
            'activate'   => 'activado',
            'deactivate' => 'desactivado',
            'delete'     => 'eliminado',
            'change_status' => 'actualizado el estado',
            'change_geo_state' => 'actualizado la ubicación',
            default => throw new \Exception("Acción desconocida para la etiqueta: " . $request->action),
        };

        DB::transaction(function () use ($ids, $action, $value) {
            $query = Client::whereIn('id', $ids);

            match ($action) {
                'activate'   => $query->update(['active' => 1]),
                'deactivate' => $query->update(['active' => 0]),
                'delete'     => $query->delete(),
                'change_status' => $query->update(['estado_cliente_id' => $value]),
                'change_geo_state' => $query->update(['state_id' => $value]),
                default => throw new \Exception("Acción no permitida"),
            };

        });

        // GUARDAMOS EN SESIÓN para que el Toast de index.blade.php lo lea al recargar
        $mensaje = "Se han {$actionLabel} correctamente {$count} registros.";
        session()->flash('success', $mensaje);

        return response()->json([
            'success' => true, 
            'message' => $mensaje
        ]);
    }


    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $config = ConfiguracionGeneral::actual();
        $estados = EstadosCliente::activos()->get();
        $states = State::orderBy('name')->get();
        $types = ['individual' => 'Persona Física', 'company' => 'Empresa / Jurídica'];
        // Pasamos el tax_label por defecto basado en la config
    $defaultTaxLabel = $config->taxIdentifierType->code ?? 'Tax ID';

        return view('clients.create', compact('estados', 'states', 'types', 'defaultTaxLabel'));
    }
    
    /**
     * Almacenar nuevo cliente
     */
    public function store(Request $request)
    {
        $config = ConfiguracionGeneral::actual();
        $data = $request->validate([
            'type' => ['required', Rule::in(['individual', 'company'])],
            'name' => 'required|string|max:255',
            'commercial_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'estado_cliente_id' => 'required|exists:estados_clientes,id',
            'state_id' => 'required|exists:states,id',
            'city' => 'required|string|max:100',
            'tax_id' => 'nullable|string|max:50',
        ]);

        $entityType = ($data['type'] === 'individual') ? 'person' : 'company';

        $identifier = TaxIdentifierType::where('country_id', $config->country_id)
        ->where(function($q) use ($entityType) {
            $q->where('entity_type', $entityType)->orWhere('entity_type', 'both');
        })->first();

        $data['tax_identifier_type_id'] = $identifier?->id;
        
        $client = Client::create(array_merge($data, ['active' => true]));

        return redirect()
            ->route('clients.index')
            ->with('success', "Cliente {$client->name} creado correctamente.");
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Client $client)
    {
        $estados = EstadosCliente::activos()->get();
        $states = State::orderBy('name')->get();
        $types = ['individual' => 'Persona Física', 'company' => 'Empresa / Jurídica'];

        return view('clients.edit', compact('client', 'estados', 'states', 'types'));
    }

    /**
     * Actualizar cliente
     */
    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['individual', 'company'])],
            'name' => 'required|string|max:255',
            'commercial_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'estado_cliente_id' => 'required|exists:estados_clientes,id',
            'state_id' => 'required|exists:states,id',
            'city' => 'required|string|max:100',
            'tax_id' => 'nullable|string|max:50',
        ]);

        $client->update($data);

        return redirect()
            ->route('clients.index')
            ->with('success', "Cliente {$client->name} actualizado correctamente.");
    }

    /**
     * Alternar estado activo/inactivo
     */
    public function toggleEstado(Client $client)
    {
        $client->toggleActivo();
        $status = $client->active ? 'activado' : 'desactivado';
        
        return redirect()->back()
            ->with('success', "El cliente ha sido {$status}.");
    }

    /**
     * Eliminar (Soft Delete)
     */
    public function destroy(Client $client)
    {
        // El trait manejará la lógica de borrado suave y redirección
        return $this->destroyTrait($client, null);
    }

    /* ===========================
     |  CONFIGURACIÓN DEL TRAIT
     =========================== */
    protected function getModelClass(): string { return Client::class; }
    protected function getViewFolder(): string { return 'clients'; }
    protected function getRouteIndex(): string { return 'clients.index'; }
    protected function getRouteEliminadas(): string { return 'clients.eliminados'; }
    protected function getEntityName(): string { return 'Cliente'; }
}