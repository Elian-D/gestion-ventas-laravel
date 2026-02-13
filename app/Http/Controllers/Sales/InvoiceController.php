<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales\Invoice;
use App\Services\Sales\InvoicesServices\InvoiceCatalogService;
use App\Filters\Sales\InvoiceFilters\InvoiceFilters;
use App\Tables\SalesTables\InvoiceTable;
use App\Traits\SoftDeletesTrait;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\Sales\Invoices\ExportInvoiceRequest;
use App\Exports\Sales\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    use SoftDeletesTrait;

    public function __construct(
        protected InvoiceCatalogService $catalogService
    ) {}

    /**
     * Vista principal: Listado de documentos legales.
     */
    public function index(Request $request)
    {
        $visibleColumns = $request->input('columns', InvoiceTable::defaultDesktop());
        $perPage = $request->input('per_page', 10);

        // Aplicación del Pipeline de Filtros sobre la relación con ventas y clientes
        $invoices = (new InvoiceFilters($request))
            ->apply(Invoice::query()->withIndexRelations())
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $catalogs = $this->catalogService->getForFilters();

        if ($request->ajax()) {
            return view('sales.invoices.partials.table', [
                'items'          => $invoices,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => InvoiceTable::allColumns(),
                'defaultDesktop' => InvoiceTable::defaultDesktop(),
                'defaultMobile'  => InvoiceTable::defaultMobile(),
            ])->render();
        }

        return view('sales.invoices.index', array_merge(
            [
                'items'          => $invoices,
                'visibleColumns' => $visibleColumns,
                'allColumns'     => InvoiceTable::allColumns(),
                'defaultDesktop' => InvoiceTable::defaultDesktop(),
                'defaultMobile'  => InvoiceTable::defaultMobile(),
            ],
            $catalogs
        ));
    }

    public function show(Invoice $invoice)
    {
        // CARGA PROFUNDA: Entramos hasta la secuencia para obtener la fecha de vencimiento
        $invoice->load([
            'sale.items.product', 
            'sale.client', 
            'sale.ncfLog.type', 
            'sale.ncfLog.sequence', // <--- FUNDAMENTAL
        ]);
        
        $formats = Invoice::getFormats();
        return view('sales.invoices.show', compact('invoice', 'formats'));
    }

    public function preview(Invoice $invoice)
    {
        // El preview también necesita la data del NCF para mostrarla en el iframe
        $invoice->load([
            'sale.items.product', 
            'sale.client',
            'sale.ncfLog.type',
            'sale.ncfLog.sequence' // <--- FUNDAMENTAL
        ]);
        
        $viewMap = [
            Invoice::FORMAT_TICKET => 'ticket',
            Invoice::FORMAT_ROUTE  => 'ticket',
            Invoice::FORMAT_LETTER => 'full',
        ];

        $viewName = $viewMap[$invoice->format_type] ?? 'ticket';

        return view("sales.invoices.formats.{$viewName}", [
            'invoice' => $invoice
        ]);
    }



    public function print(Invoice $invoice)
    {
        // CARGA DE RELACIONES CRUCIAL PARA NCF
        $invoice->load([
            'sale.items.product', 
            'sale.client', 
            'sale.user',
            'sale.ncfLog.type', // Carga el tipo de NCF (Crédito Fiscal, Consumo, etc.)
        ]);

        // 1. Si es formato TICKET o RUTA
        if ($invoice->format_type !== Invoice::FORMAT_LETTER) {
            return view('sales.invoices.print', [
                'invoice' => $invoice,
                'format'  => $invoice->format_type 
            ]);
        }

        // 2. Si es formato CARTA
        $pdf = Pdf::loadView('sales.invoices.formats.full', compact('invoice'))
                    ->setPaper('letter', 'portrait');

        return $pdf->stream("Factura-{$invoice->invoice_number}.pdf");
    }


    /**
     * Exportación filtrada de facturas a Excel.
     */
    public function export(ExportInvoiceRequest $request)
    {
        try {
            // Aplicamos los mismos filtros que en la tabla
            $query = (new InvoiceFilters($request))
                ->apply(Invoice::query());

            $fileName = 'historial-facturacion-' . now()->format('d-m-Y-H-i') . '.xlsx';

            return Excel::download(new InvoicesExport($query), $fileName);
            
        } catch (Exception $e) {
            return back()->with('error', "No se pudo generar el reporte: " . $e->getMessage());
        }
    }

    // Requerimientos para SoftDeletesTrait (Auditoría técnica)
    protected function getModelClass(): string { return Invoice::class; }
    protected function getViewFolder(): string { return 'sales.invoices'; }
    protected function getRouteIndex(): string { return 'sales.invoices.index'; }
    protected function getRouteEliminadas(): string { return 'sales.invoices.eliminadas'; }
    protected function getEntityName(): string { return 'Factura'; }
}