<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\Receivable\StoreReceivableRequest;
use App\Http\Requests\Accounting\Receivable\UpdateReceivableRequest;
use App\Models\Accounting\Receivable;
use App\Services\Accounting\Receivable\ReceivableService;
use App\Services\Accounting\Receivable\ReceivableCatalogService;
use App\Filters\Accounting\ReceivablesFilters\ReceivableFilters;
use App\Tables\AccountingTables\ReceivableTable;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
    use SoftDeletesTrait;

    public function __construct(
        protected ReceivableService $service,
        protected ReceivableCatalogService $catalogService
    ) {}

    public function index(Request $request)
    {
        $visibleColumns = $request->input('columns', ReceivableTable::defaultDesktop());
        $perPage = $request->input('per_page', 15);

        // Aplicación del Pipeline de Filtros
        $receivables = (new ReceivableFilters($request))
            ->apply(Receivable::query()->with(['client', 'journalEntry']))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $catalogs = $this->catalogService->getForFilters();

        if ($request->ajax()) {
            return view('accounting.receivables.partials.table', [
                'items'          => $receivables,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => ReceivableTable::allColumns(),
                'defaultDesktop' => ReceivableTable::defaultDesktop(),
                'defaultMobile'  => ReceivableTable::defaultMobile(),
            ])->render();
        }

        return view('accounting.receivables.index', array_merge(
            [
                'items'          => $receivables,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => ReceivableTable::allColumns(),
                'defaultDesktop' => ReceivableTable::defaultDesktop(),
                'defaultMobile'  => ReceivableTable::defaultMobile(),
            ],
            $catalogs
        ));
    }

    public function create()
    {
        return view('accounting.receivables.create', $this->catalogService->getForForm());
    }

    public function store(StoreReceivableRequest $request)
    {
        try {
            $this->service->createReceivable($request->validated());

            return redirect()
                ->route('accounting.receivables.index')
                ->with('success', "Cuenta por cobrar registrada y saldo de cliente actualizado.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(Receivable $receivable)
    {
        return view('accounting.receivables.edit', [
            'item'     => $receivable->load('client'),
            'catalogs' => $this->catalogService->getForForm()
        ]);
    }

    public function update(UpdateReceivableRequest $request, Receivable $receivable)
    {
        try {
            $this->service->updateReceivable($receivable, $request->validated());

            return redirect()
                ->route('accounting.receivables.index')
                ->with('success', "Registro actualizado y saldo de cliente recalculado.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

        /**
     * Registra un abono a la cuenta por cobrar
     */
    public function registerPayment(Request $request, Receivable $receivable)
    {
        // Validación rápida del monto
        $data = $request->validate([
            'payment_amount' => [
                'required', 
                'numeric', 
                'min:0.01', 
                "max:{$receivable->current_balance}"
            ],
            'payment_date'   => 'required|date',
            'reference'      => 'nullable|string|max:100',
        ], [
            'payment_amount.max' => 'El abono no puede exceder el saldo actual de $' . number_format($receivable->current_balance, 2)
        ]);

        try {
            // Invocamos al servicio que ya tienes
            $this->service->registerPayment($receivable, $data);

            return redirect()
                ->route('accounting.receivables.index')
                ->with('success', "Abono de $" . number_format($data['payment_amount'], 2) . " registrado correctamente.");
                
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Anulación lógica de la deuda (Mantiene integridad)
     */
    public function cancel(Receivable $receivable)
    {
        try {
            $this->service->cancelReceivable($receivable);
            return back()->with('success', "La cuenta por cobrar ha sido anulada.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $receivable = Receivable::findOrFail($id);

        if ($receivable->status === Receivable::STATUS_PAID) {
            return back()->with('error', "No se puede eliminar una factura ya pagada.");
        }

        return $this->destroyTrait($receivable);
    }

    // Requerimientos del SoftDeletesTrait
    protected function getModelClass(): string { return Receivable::class; }
    protected function getViewFolder(): string { return 'accounting.receivables'; }
    protected function getRouteIndex(): string { return 'accounting.receivables.index'; }
    protected function getRouteEliminadas(): string { return 'accounting.receivables.eliminados'; }
    protected function getEntityName(): string { return 'Cuenta por Cobrar'; }
}