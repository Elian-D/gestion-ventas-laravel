<?php

namespace App\Http\Controllers\Clients;

use App\Models\Clients\Client;
use App\Models\Configuration\EstadosCliente;
use App\Models\Geo\State;
use App\Http\Controllers\Controller;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    use SoftDeletesTrait;

    /**
     * Listado principal de clientes
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $estadoFiltro = $request->query('estado'); // activo | inactivo

        $clients = Client::with(['estadoCliente', 'state']) // Eager Loading para optimizar
            ->when($search, fn($q) => $q->search($search))
            ->when($estadoFiltro, function($q) use ($estadoFiltro) {
                return $estadoFiltro === 'activo' ? $q->activos() : $q->where('active', false);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('clients.index', compact('clients', 'search', 'estadoFiltro'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $estados = EstadosCliente::activos()->get();
        $states = State::orderBy('name')->get();
        $types = ['individual' => 'Persona Física', 'company' => 'Empresa / Jurídica'];

        return view('clients.create', compact('estados', 'states', 'types'));
    }

    /**
     * Almacenar nuevo cliente
     */
    public function store(Request $request)
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