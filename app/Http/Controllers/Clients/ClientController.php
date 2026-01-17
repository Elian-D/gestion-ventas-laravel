<?php

namespace App\Http\Controllers\Clients;

use App\Exports\Clients\ClientsTemplateExport;
use App\Models\Clients\Client;
use App\Models\Clients\BusinessType;
use App\Models\Configuration\EstadosCliente;
use App\Models\Geo\State;
use App\Http\Controllers\Controller;
use App\Models\Configuration\TaxIdentifierType;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Filters\Client\ClientFilters;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\Clients\ClientsExport;
use App\Imports\ClientsImport;
use Maatwebsite\Excel\Facades\Excel;

use Maatwebsite\Excel\Validators\ValidationException;

class ClientController extends Controller
{
    use SoftDeletesTrait;



    public function index(Request $request)
    {
        // Autorizamos que se pueden hacer bulk actions
        $bulkActions = true;

        $config = general_config();
        $countryId = $config?->country_id;

        $states = $countryId 
                ? State::byCountry($countryId)
                    ->select('id', 'name')
                    ->orderBy('name')
                    ->get() 
                : collect();

        $allColumns = [
            'id'               => 'ID',
            'name'             => 'Nombre Cliente',
            'tax_identifier_types' => 'Tipo Identificador Fiscal',
            'tax_id'           => 'Identificador Fiscal',
            'type'             => 'Tipo de Cliente',
            'email'            => 'Email',
            'phone'            => 'Teléfono',
            'city'             => 'Ciudad',
            'state'            => 'Estado/Provincia',
            'estado_cliente'   => 'Estado del Cliente',
            'estado_operativo' => 'Estado Operativo',
            'created_at'       => 'Fecha Creación',
            'updated_at'       => 'Última Actualización'
        ];
        $defaultDesktop = ['id', 'name', 'tax_id', 'city', 'state', 'estado_cliente', 'estado_operativo'];
        $defaultMobile = ['id','name'];

        $visibleColumns = $request->input('columns', $defaultDesktop);
        $perPage = $request->input('per_page', 10);

        $clients = (new ClientFilters($request))
            ->apply(
                Client::query()
                    ->with([
                        'estadoCliente:id,nombre,permite_operar,clase_fondo,clase_texto',
                        'state:id,name',
                        'taxIdentifierType:id,name,code',
                    ])
            )
            ->paginate($perPage)
            ->withQueryString();

        
        $estadosClientes = EstadosCliente::select('id', 'nombre')->get();
        $tiposNegocio = BusinessType::select('id', 'nombre')->get();

        if ($request->ajax()) {
            return view('clients.partials.table', compact(
                'clients',
                'allColumns',
                'visibleColumns',
                'defaultDesktop',
                'defaultMobile',
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
        'defaultDesktop',
        'defaultMobile',
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

        try {

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
            
        } catch (\Exception $e) {
            // Logueamos el error real para nosotros
            Log::error("Error en acción masiva: " . $e->getMessage());
            
            // Enviamos un mensaje amigable al usuario
            return response()->json([
                'success' => false, 
                'message' => 'No se pudo completar la operación. Verifique las restricciones de los registros.'
            ], 422);
        }
    }

    // Exportar clientes a Excel
    public function export(Request $request) 
    {
        // 1. Aplicamos tus filtros existentes
        $query = (new ClientFilters($request))->apply(Client::query());

        // 2. IMPORTANTE: Ignoramos las columnas seleccionadas de la vista    
        return Excel::download(
            new ClientsExport($query), 
            'respaldo-clientes-' . now()->format('d-m-Y-h:ia') . '.xlsx'
        );
    }

    /**
     * Muestra la vista de importación
     */
    public function showImportForm()
    {
        return view('clients.import');
    }

    /**
     * Descarga la plantilla base de clientes
     */
    public function downloadTemplate()
    {
        // La Facade Excel se llama de forma estática correctamente
        return Excel::download(new ClientsTemplateExport, 'plantilla-importacion-clientes.xlsx');
    }


    /**
     * Procesa la importación
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // Aumenté a 10MB
        ]);

        try {
            // Desactivar logs temporalmente para máximo rendimiento
            DB::connection()->disableQueryLog();
            
            Excel::import(new ClientsImport, $request->file('file'));
            
            return redirect()
                ->route('clients.index')
                ->with('success', 'Importación completada exitosamente.');
                
        } catch (ValidationException $e) {
            return back()->withErrors(['file' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Error en la importación: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $config = general_config();
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
        $config = general_config();
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