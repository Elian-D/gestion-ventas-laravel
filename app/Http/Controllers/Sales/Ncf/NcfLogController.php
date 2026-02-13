<?php

namespace App\Http\Controllers\Sales\Ncf;

use App\Http\Controllers\Controller;
use App\Models\Sales\Ncf\NcfLog;
use App\Filters\Sales\Ncf\NcfLogFilters;
use App\Tables\SalesTables\Ncf\NcfLogTable;
use App\Services\Sales\Ncf\NcfCatalogService;
use App\Services\Sales\Ncf\NcfReportService;
use App\Exports\Sales\Ncf\NcfLogsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class NcfLogController extends Controller
{
    public function __construct(
        protected NcfCatalogService $catalog,
        protected NcfReportService $reportService
    ) {}


    public function index(Request $request)
    {
        $visibleColumns = $request->input('columns', NcfLogTable::defaultDesktop());
        $perPage = $request->input('per_page', 25);

        $logs = (new NcfLogFilters($request))
            ->apply(NcfLog::query()->with(['sale.client', 'type', 'user']))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        // USAMOS EL MÉTODO ESPECÍFICO PARA LOGS
        $catalog = $this->catalog->getForLogs(); 

        $data = array_merge([
            'items'          => $logs,
            'visibleColumns' => $visibleColumns,
            'allColumns'     => NcfLogTable::allColumns(),
            'defaultDesktop' => NcfLogTable::defaultDesktop(),
            'defaultMobile'  => NcfLogTable::defaultMobile(),
        ], $catalog);

        if ($request->ajax()) {
            return view('sales.ncf.logs.partials.table', $data)->render();
        }

        return view('sales.ncf.logs.index', $data);
    }
    /**
     * Exportación a Excel para revisión interna (Auditoría)
     */
    public function exportExcel(Request $request)
    {
        $query = (new NcfLogFilters($request))
            ->apply(NcfLog::query());

        $fileName = 'auditoria-ncf-' . now()->format('Ymd-Hi') . '.xlsx';

        return Excel::download(new NcfLogsExport($query), $fileName);
    }

    /**
     * Generación de archivo TXT formato DGII (Reporte 607)
     */
    public function exportTxt(Request $request)
    {
        // 1. Limpiamos el periodo antes de validar si viene con guion (ej: 2024-02 -> 202402)
        if ($request->has('periodo')) {
            $cleanPeriodo = str_replace('-', '', $request->input('periodo'));
            $request->merge(['periodo' => $cleanPeriodo]);
        }

        // 2. Ahora validamos el string limpio (6 dígitos: YYYYMM)
        $request->validate([
            'periodo' => 'required|digits:6'
        ], [
            'periodo.digits' => 'El periodo debe tener el formato AAAAMM (ejemplo: 202402).'
        ]);

        $logs = (new NcfLogFilters($request))
                ->apply(NcfLog::query()->with(['sale.client'])) // Eager loading de client
                ->where('status', NcfLog::STATUS_USED)
                ->get();

        if ($logs->isEmpty()) {
            return back()->with('error', 'No hay registros de NCF usados para el periodo seleccionado.');
        }

        $content = $this->reportService->generate607Txt($logs, $request->periodo);
        
        $fileName = "DGII_607_{$request->periodo}_" . now()->format('His') . ".txt";
        
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', "attachment; filename={$fileName}");
    }
}