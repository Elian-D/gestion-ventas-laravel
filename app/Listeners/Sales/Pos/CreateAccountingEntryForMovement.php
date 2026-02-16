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

        // Idempotencia: No duplicar asientos
        if ($movement->accounting_entry_id) return;

        try {
            $session = $movement->session;
            $terminal = $session->terminal;
            
            // Prioridad: 1. Cuenta de la Terminal, 2. Fallback a Caja General (1.1.01)
            $terminalAccount = $terminal->cash_account_id 
                ?? AccountingAccount::where('code', '1.1.01')->value('id');

            if (!$terminalAccount) {
                throw new \Exception("No se encontró una cuenta contable de destino para la terminal #{$terminal->id}");
            }

            // La contrapartida (Gasto, Ingreso, etc.)
            $contraAccount = $movement->accounting_account_id;

            if ($movement->isEntry()) {
                // ENTRADA: Aumenta Activo (Caja) / Aumenta Pasivo o Capital o Ingreso (Contra)
                $items = [
                    ['accounting_account_id' => $terminalAccount, 'debit' => $movement->amount, 'credit' => 0],
                    ['accounting_account_id' => $contraAccount, 'debit' => 0, 'credit' => $movement->amount],
                ];
            } else {
                // SALIDA: Aumenta Gasto o disminuye Pasivo (Contra) / Disminuye Activo (Caja)
                $items = [
                    ['accounting_account_id' => $contraAccount, 'debit' => $movement->amount, 'credit' => 0],
                    ['accounting_account_id' => $terminalAccount, 'debit' => 0, 'credit' => $movement->amount],
                ];
            }

            $entry = $this->journalService->create([
                'entry_date'  => now(),
                'reference'   => "POS-MOV-{$movement->id}",
                'description' => "Movimiento POS {$session->terminal->name}(#{$session->id}): {$movement->reason}",
                'status'      => JournalEntry::STATUS_POSTED,
                'items'       => $items
            ]);

            $movement->update(['accounting_entry_id' => $entry->id]);

        } catch (\Exception $e) {
            Log::error("Error contable POS #{$movement->id}: " . $e->getMessage());
        }
    }
}