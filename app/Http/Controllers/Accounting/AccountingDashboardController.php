<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\AccountingAccount;
use App\Models\Accounting\JournalItem;
use App\Models\Accounting\JournalEntry;
use Illuminate\Support\Facades\DB;

class AccountingDashboardController extends Controller
{
    
    public function __invoke()
    {
        // ... (tus balances anteriores se mantienen)
        $cashBalance = $this->getAccountBalanceByCode('1.1.01');
        $cxcBalance = $this->getAccountBalanceByCode('1.1.02');
        $inventoryValue = $this->getAccountBalanceByCode('1.1.03');

        // 1. Cuentas por Pagar (Pasivo 2.1)
        // Usamos ABS porque el pasivo es de naturaleza acreedora (negativo en DB)
        $cxpBalance = abs($this->getAccountBalanceByCode('2.1')); 

        // 2. Cálculo de Liquidez (Activo Circulante / Pasivo Circulante)
        $currentAssets = $cashBalance + $cxcBalance + $inventoryValue;
        $liquidityRatio = $cxpBalance > 0 ? ($currentAssets / $cxpBalance) : $currentAssets;

        // 3. Distribución de Gastos (Cuentas que empiezan con 5.2, 5.3, etc.)
        $expenseDistribution = $this->getExpenseDistribution();

        // 4. Datos previos
        $income = abs($this->getAccountBalanceByCode('4.1'));
        $costOfSales = $this->getAccountBalanceByCode('5.1');
        $grossProfit = $income - $costOfSales;
        $monthlyPerformance = $this->getMonthlyPerformance();
        

        return view('accounting.dashboard', [
            'stats' => [
                'cash_balance'    => $cashBalance,
                'cxc_balance'     => $cxcBalance,
                'cxp_balance'     => $cxpBalance, // Nuevo
                'inventory_value' => $inventoryValue,
                'gross_profit'    => $grossProfit,
                'profit_margin'   => $income > 0 ? ($grossProfit / $income) * 100 : 0,
                'liquidity_ratio' => $liquidityRatio, // Nuevo
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
                'expenses' => [ // Nuevo
                    'labels' => $expenseDistribution->pluck('name'),
                    'values' => $expenseDistribution->pluck('total'),
                ]
            ],
            'recentEntries' => JournalEntry::with('creator')->latest()->take(5)->get()
        ]);
    }

    private function getExpenseDistribution()
    {
        return JournalItem::select(
                'accounting_accounts.name',
                DB::raw('SUM(journal_items.debit - journal_items.credit) as total')
            )
            ->join('accounting_accounts', 'journal_items.accounting_account_id', '=', 'accounting_accounts.id')
            ->where('accounting_accounts.code', 'like', '5%') // Todos los gastos
            ->where('accounting_accounts.code', 'not like', '5.1%') // Excluir costo de ventas
            ->groupBy('accounting_accounts.name')
            ->having('total', '>', 0)
            ->get();
    }

    private function getAccountBalanceByCode(string $code)
    {
        // Sumamos todos los débitos - créditos de la cuenta y sus hijas
        return JournalItem::whereHas('account', function($q) use ($code) {
            $q->where('code', 'like', $code . '%');
        })->selectRaw('SUM(debit - credit) as balance')->value('balance') ?? 0;
    }

    private function getMonthlyPerformance()
    {
        return JournalItem::select(
            DB::raw("DATE_FORMAT(journal_entries.entry_date, '%b') as month"),
            DB::raw("SUM(CASE WHEN accounting_accounts.code LIKE '4%' THEN (credit - debit) ELSE 0 END) as income"),
            DB::raw("SUM(CASE WHEN accounting_accounts.code LIKE '5%' THEN (debit - credit) ELSE 0 END) as costs")
        )
        ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
        ->join('accounting_accounts', 'journal_items.accounting_account_id', '=', 'accounting_accounts.id')
        ->where('journal_entries.entry_date', '>=', now()->subMonths(6))
        ->groupBy('month', DB::raw("MONTH(journal_entries.entry_date)"))
        ->orderBy(DB::raw("MONTH(journal_entries.entry_date)"))
        ->get();
    }
}