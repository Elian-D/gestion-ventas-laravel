<?php

namespace App\Http\Controllers\Sales\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Sales\Pos\PosSession;
use App\Services\Sales\Pos\PosSessionServices\{PosSessionService, PosSessionCatalogService};
use App\Filters\Sales\Pos\SessionFilters\PosSessionFilters;
use App\Http\Requests\Sales\Pos\PosSessions\{OpenSessionRequest, CloseSessionRequest, UpdatePosSessionRequest};
use Illuminate\Http\Request;
use App\Tables\SalesTables\Pos\PosSessionTable;

class PosSessionController extends Controller
{
    use AuthorizesRequests;
    
    public function __construct(
        protected PosSessionService $service,
        protected PosSessionCatalogService $catalogService
    ) {}

    /**
     * Listado histórico de sesiones con Pipeline de Filtros.
     */
    public function index(Request $request, PosSessionFilters $filters)
    {
        $this->authorize('pos sessions history');

        // 1. Parámetros de Columnas (UI)
        $visibleColumns = $request->input('columns', PosSessionTable::defaultDesktop());
        $perPage = $request->input('per_page', 15);

        // 2. Aplicar Filtros y Query
        $sessions = $filters->apply(
            PosSession::with(['terminal', 'user'])
        )
        ->orderBy('opened_at', 'desc')
        ->paginate($perPage)
        ->withQueryString();

        // 3. Respuesta AJAX para recarga de tabla
        if ($request->ajax()) {
            return view('sales.pos.sessions.partials.table', [
                'sessions'       => $sessions,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => PosSessionTable::allColumns(),
                'defaultDesktop' => PosSessionTable::defaultDesktop(),
                'defaultMobile'  => PosSessionTable::defaultMobile(),
            ])->render();
        }

        // 4. Carga de Catálogos para los Filtros (Selects de Terminales y Usuarios)
        $catalog = $this->catalogService->getForFilters();
        $catalogForms = $this->catalogService->getForForm(); // Para el modal de apertura (terminales disponibles)

        // 5. Retorno de vista con todas las variables necesarias
        return view('sales.pos.sessions.index', array_merge(
            [
                'sessions'       => $sessions,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => PosSessionTable::allColumns(),
                'defaultDesktop' => PosSessionTable::defaultDesktop(),
                'defaultMobile'  => PosSessionTable::defaultMobile(),
                'available_terminals' => $catalogForms['available_terminals'],
            ],
            $catalog // Esto ya trae 'terminals' y 'users'
        ));
    }

    /**
     * Acción de Apertura (Store).
     */
    public function store(OpenSessionRequest $request)
    {
        try {
            $session = $this->service->open($request->validated());
            
            return redirect()->back()->with('success', "Sesión abierta correctamente en {$session->terminal->name}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show(PosSession $posSession)
    {
        $this->authorize('pos sessions history');
        
        // Cargamos relaciones necesarias incluyendo la cuenta contable de cada movimiento
        $posSession->load(['terminal', 'user', 'cashMovements.user', 'cashMovements.account']);
        
        $cashIn = $posSession->cashMovements->where('type', 'in')->sum('amount');
        $cashOut = $posSession->cashMovements->where('type', 'out')->sum('amount');
        
        // Obtenemos las cuentas del catálogo para el modal de movimientos
        $catalog = $this->catalogService->getForForm();
        
        return view('sales.pos.sessions.show', array_merge(
            compact('posSession', 'cashIn', 'cashOut'),
            [
                'income_accounts' => $catalog['income_accounts'],
                'expense_accounts' => $catalog['expense_accounts']
            ]
        ));
    }
    /**
     * Acción de Cierre (Patch).
     * Asegúrate de que el nombre coincida: {pos_session} -> $posSession
     */
    public function close(CloseSessionRequest $request, PosSession $posSession)
    {
        try {
            // Ejecuta la lógica de liquidación del servicio
            $this->service->close($posSession, $request->validated());
            
            return redirect()->route('sales.pos.sessions.index')
                ->with('success', 'Sesión cerrada y arqueada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Edición administrativa (Notas).
     */
    public function update(UpdatePosSessionRequest $request, PosSession $posSession)
    {
        $this->service->update($posSession, $request->validated());

        return redirect()->back()->with('success', 'Sesión actualizada correctamente.');
    }
}