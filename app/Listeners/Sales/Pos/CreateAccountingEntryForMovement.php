<?php

namespace App\Listeners\Sales\Pos;

use App\Events\Sales\Pos\CashMovementRegistered;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\AccountingAccount;
use App\Services\Accounting\JournalEntries\JournalEntryService;
use Illuminate\Support\Facades\Log;

class CreateAccountingEntryForMovement
{
    public function __construct(protected JournalEntryService $journalService) {}

    public function handle(CashMovementRegistered $event): void
    {
        $movement = $event->movement;
        $session = $movement->session;

        try {
            // 1. Obtener Cuenta de la Terminal (o la de Caja general por defecto)
            $terminalAccountId = $session->terminal->cash_account_id 
                ?? AccountingAccount::where('code', '1.1.01')->value('id');

            // 2. Obtener Cuenta de Gastos (o contrapartida)
            $expenseAccountId = AccountingAccount::where('code', '5.3')->value('id');

            if (!$terminalAccountId || !$expenseAccountId) {
                Log::error("Error Contable: Cuentas no encontradas para movimiento POS #{$movement->id}");
                return;
            }

            // 3. Definir Items según el tipo
            $items = [];
            if ($movement->isEntry()) { // TYPE_IN
                $items = [
                    ['accounting_account_id' => $terminalAccountId, 'debit' => $movement->amount, 'credit' => 0, 'note' => $movement->reason],
                    ['accounting_account_id' => $expenseAccountId, 'debit' => 0, 'credit' => $movement->amount, 'note' => 'Ajuste entrada']
                ];
            } else { // TYPE_OUT
                $items = [
                    ['accounting_account_id' => $expenseAccountId, 'debit' => $movement->amount, 'credit' => 0, 'note' => $movement->reason],
                    ['accounting_account_id' => $terminalAccountId, 'debit' => 0, 'credit' => $movement->amount, 'note' => "Salida terminal: {$session->terminal->name}"]
                ];
            }

            // 4. Crear Asiento
            $entry = $this->journalService->create([
                'entry_date'  => now(),
                'reference'   => "POS-MOV-{$movement->id}",
                'description' => "Movimiento POS: {$movement->reason}",
                'status'      => JournalEntry::STATUS_POSTED,
                'items'       => $items
            ]);

            // 5. Vincular el asiento al movimiento
            $movement->update(['accounting_entry_id' => $entry->id]);

        } catch (\Exception $e) {
            Log::error("Fallo al crear asiento contable para movimiento POS: " . $e->getMessage());
        }
    }
}