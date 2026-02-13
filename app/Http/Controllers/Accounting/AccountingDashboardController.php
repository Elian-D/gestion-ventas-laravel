<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\AccountingAccount;
use App\Models\Accounting\JournalItem;
use App\Models\Accounting\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingDashboardController extends Controller
{
    
    public function __invoke(Request $request)
    {
        // ===== SISTEMA DE FILTROS (IGUAL AL DASHBOARD DE VENTAS) =====
        $range = $request->get('range', '30days');
        
        $startDay = now()->subDays(30)->startOfDay();
        $endDay = now()->endOfDay();

        switch ($range) {
            case 'today':
                $startDay = now()->startOfDay();
                break;
            case '7days':
                $startDay = now()->subDays(7)->startOfDay();
                break;
            case 'this_month':
                $startDay = now()->startOfMonth()->startOfDay();
                break;
            case 'this_year':
                $startDay = now()->startOfYear()->startOfDay();
                break;
            case 'custom':
                $startDay = \Carbon\Carbon::parse($request->get('start_date'))->startOfDay();
                $endDay = \Carbon\Carbon::parse($request->get('end_date'))->endOfDay();
                break;
        }

        // ===== BALANCES DE CUENTAS (CON FILTRO DE FECHA) =====
        $cashBalance = $this->getAccountBalanceByCode('1.1.01', $startDay, $endDay);
        $cxcBalance = $this->getAccountBalanceByCode('1.1.02', $startDay, $endDay);
        $inventoryValue = $this->getAccountBalanceByCode('1.1.03', $startDay, $endDay);
        $cxpBalance = abs($this->getAccountBalanceByCode('2.1', $startDay, $endDay));

        // ===== CÁLCULOS DE RENDIMIENTO =====
        $income = abs($this->getAccountBalanceByCode('4.1', $startDay, $endDay));
        $costOfSales = $this->getAccountBalanceByCode('5.1', $startDay, $endDay);
        $grossProfit = $income - $costOfSales;

        // ===== LIQUIDEZ =====
        $currentAssets = $cashBalance + $cxcBalance + $inventoryValue;
        $liquidityRatio = $cxpBalance > 0 ? ($currentAssets / $cxpBalance) : $currentAssets;

        // ===== NUEVO: FLUJO DE CAJA (Ingresos totales - Egresos totales) =====
        $totalInflows = $this->getTotalInflows($startDay, $endDay);
        $totalOutflows = $this->getTotalOutflows($startDay, $endDay);
        $cashFlow = $totalInflows - $totalOutflows;

        // ===== NUEVO: SISTEMA DE ALERTAS FINANCIERAS =====
        $alerts = $this->generateFinancialAlerts($liquidityRatio, $cashFlow, $cxcBalance, $cxpBalance);

        // ===== DATOS PARA GRÁFICOS =====
        $monthlyPerformance = $this->getMonthlyPerformance($startDay, $endDay);
        $expenseDistribution = $this->getExpenseDistribution($startDay, $endDay);
        $cashflowTimeline = $this->getCashflowTimeline($startDay, $endDay);

        return view('accounting.dashboard', [
            'stats' => [
                'cash_balance'    => $cashBalance,
                'cxc_balance'     => $cxcBalance,
                'cxp_balance'     => $cxpBalance,
                'inventory_value' => $inventoryValue,
                'gross_profit'    => $grossProfit,
                'profit_margin'   => $income > 0 ? ($grossProfit / $income) * 100 : 0,
                'liquidity_ratio' => $liquidityRatio,
                'cash_flow'       => $cashFlow, // NUEVO
            ],
            'charts' => [
                'performance' => [
                    'labels' => $monthlyPerformance->pluck('month'),
                    'income' => $monthlyPerformance->pluck('income'),
                    'costs'  => $monthlyPerformance->pluck('costs'),
                ],
                'composition' => [
                    'labels' => ['Disponible', 'Por Cobrar', 'Inventario'],
                    'values' => [max(0, $cashBalance), max(0, $cxcBalance), max(0, $inventoryValue)]
                ],
                'expenses' => [
                    'labels' => $expenseDistribution->pluck('name'),
                    'values' => $expenseDistribution->pluck('total'),
                ],
                'cashflow' => [ // NUEVO
                    'labels' => $cashflowTimeline->pluck('date'),
                    'inflows' => $cashflowTimeline->pluck('inflows'),
                    'outflows' => $cashflowTimeline->pluck('outflows'),
                ]
            ],
            'alerts' => $alerts, // NUEVO
            'recentEntries' => JournalEntry::with('creator')
                ->whereBetween('entry_date', [$startDay, $endDay])
                ->latest()
                ->take(8)
                ->get(),
            'filters' => [
                'start' => $startDay->format('Y-m-d'),
                'end' => $endDay->format('Y-m-d'),
                'current_range' => $range
            ]
        ]);
    }

    // ===== NUEVO: SISTEMA DE ALERTAS INTELIGENTES =====
    private function generateFinancialAlerts($liquidityRatio, $cashFlow, $cxcBalance, $cxpBalance)
    {
        $alerts = [];

        // Alerta de Liquidez Crítica
        if ($liquidityRatio < 0.5) {
            $alerts[] = [
                'type' => 'danger',
                'color' => 'red',
                'title' => 'Liquidez Crítica',
                'message' => 'El ratio de liquidez está por debajo de 0.5. Se requiere acción inmediata para mejorar el flujo de caja.'
            ];
        } elseif ($liquidityRatio < 1.0) {
            $alerts[] = [
                'type' => 'warning',
                'color' => 'orange',
                'title' => 'Liquidez Baja',
                'message' => 'El ratio de liquidez está por debajo de 1.0. Considere optimizar cobros y gestionar gastos.'
            ];
        }

        // Alerta de Flujo de Caja Negativo
        if ($cashFlow < 0) {
            $alerts[] = [
                'type' => 'danger',
                'color' => 'red',
                'title' => 'Flujo de Caja Negativo',
                'message' => 'Los egresos superan los ingresos en $' . number_format(abs($cashFlow), 2) . '. Revisar estrategia financiera.'
            ];
        }

        // Alerta de Cuentas por Cobrar Altas
        if ($cxcBalance > ($cxpBalance * 1.5) && $cxcBalance > 10000) {
            $alerts[] = [
                'type' => 'warning',
                'color' => 'yellow',
                'title' => 'Cartera Elevada',
                'message' => 'Las cuentas por cobrar representan ' . number_format(($cxcBalance / $cxpBalance) * 100, 0) . '% de las cuentas por pagar. Acelerar cobranza.'
            ];
        }

        // Alerta Positiva - Salud Financiera Excelente
        if ($liquidityRatio >= 2.0 && $cashFlow > 0) {
            $alerts[] = [
                'type' => 'success',
                'color' => 'green',
                'title' => 'Salud Financiera Excelente',
                'message' => 'La empresa mantiene indicadores financieros óptimos. Liquidez: ' . number_format($liquidityRatio, 2)
            ];
        }

        return $alerts;
    }

    // ===== NUEVO: FLUJO DE CAJA EN TIMELINE =====
    private function getCashflowTimeline($start, $end)
    {
        return JournalItem::select(
                DB::raw("DATE_FORMAT(journal_entries.entry_date, '%d %b') as date"),
                DB::raw("SUM(CASE WHEN accounting_accounts.code LIKE '4%' THEN (credit - debit) ELSE 0 END) as inflows"),
                DB::raw("SUM(CASE WHEN accounting_accounts.code LIKE '5%' OR accounting_accounts.code LIKE '6%' THEN (debit - credit) ELSE 0 END) as outflows"),
                DB::raw('MIN(journal_entries.entry_date) as sort_date')
            )
            ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
            ->join('accounting_accounts', 'journal_items.accounting_account_id', '=', 'accounting_accounts.id')
            ->whereBetween('journal_entries.entry_date', [$start, $end])
            ->where('journal_entries.status', 'posted')
            ->groupBy('date')
            ->orderBy('sort_date')
            ->get();
    }

    // ===== NUEVO: TOTAL DE INGRESOS =====
    private function getTotalInflows($start, $end)
    {
        return JournalItem::whereHas('entry', function($q) use ($start, $end) {
                $q->whereBetween('entry_date', [$start, $end])
                  ->where('status', 'posted');
            })
            ->whereHas('account', function($q) {
                $q->where('code', 'like', '4%'); // Cuentas de ingreso
            })
            ->selectRaw('SUM(credit - debit) as total')
            ->value('total') ?? 0;
    }

    // ===== NUEVO: TOTAL DE EGRESOS =====
    private function getTotalOutflows($start, $end)
    {
        return JournalItem::whereHas('entry', function($q) use ($start, $end) {
                $q->whereBetween('entry_date', [$start, $end])
                  ->where('status', 'posted');
            })
            ->whereHas('account', function($q) {
                $q->where('code', 'like', '5%') // Costos
                  ->orWhere('code', 'like', '6%'); // Gastos
            })
            ->selectRaw('SUM(debit - credit) as total')
            ->value('total') ?? 0;
    }

    // ===== DISTRIBUCIÓN DE GASTOS (ACTUALIZADO CON FILTRO) =====
    private function getExpenseDistribution($start, $end)
    {
        return JournalItem::select(
                'accounting_accounts.name',
                DB::raw('SUM(journal_items.debit - journal_items.credit) as total')
            )
            ->join('accounting_accounts', 'journal_items.accounting_account_id', '=', 'accounting_accounts.id')
            ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
            ->whereBetween('journal_entries.entry_date', [$start, $end])
            ->where('journal_entries.status', 'posted')
            ->where('accounting_accounts.code', 'like', '5%') // Todos los gastos
            ->where('accounting_accounts.code', 'not like', '5.1%') // Excluir costo de ventas
            ->groupBy('accounting_accounts.id', 'accounting_accounts.name')
            ->having('total', '>', 0)
            ->orderByDesc('total')
            ->take(5) // Top 5 gastos
            ->get();
    }

    // ===== BALANCE DE CUENTA CON FILTRO DE FECHA =====
    private function getAccountBalanceByCode(string $code, $start, $end)
    {
        return JournalItem::whereHas('account', function($q) use ($code) {
                $q->where('code', 'like', $code . '%');
            })
            ->whereHas('entry', function($q) use ($start, $end) {
                $q->whereBetween('entry_date', [$start, $end])
                  ->where('status', 'posted');
            })
            ->selectRaw('SUM(debit - credit) as balance')
            ->value('balance') ?? 0;
    }

    // ===== RENDIMIENTO MENSUAL (ACTUALIZADO CON FILTRO) =====
    private function getMonthlyPerformance($start, $end)
    {
        return JournalItem::select(
                DB::raw("DATE_FORMAT(journal_entries.entry_date, '%b') as month"),
                DB::raw("SUM(CASE WHEN accounting_accounts.code LIKE '4%' THEN (credit - debit) ELSE 0 END) as income"),
                DB::raw("SUM(CASE WHEN accounting_accounts.code LIKE '5%' THEN (debit - credit) ELSE 0 END) as costs")
            )
            ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
            ->join('accounting_accounts', 'journal_items.accounting_account_id', '=', 'accounting_accounts.id')
            ->whereBetween('journal_entries.entry_date', [$start, $end])
            ->where('journal_entries.status', 'posted')
            ->groupBy('month', DB::raw("MONTH(journal_entries.entry_date)"))
            ->orderBy(DB::raw("MONTH(journal_entries.entry_date)"))
            ->get();
    }
}