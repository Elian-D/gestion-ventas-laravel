<?php

namespace App\Services\Accounting\Receivable;

use App\Models\Accounting\{AccountingAccount, Receivable, JournalEntry};
use App\Models\Clients\Client;
use App\Services\Accounting\JournalEntries\JournalEntryService;
use Illuminate\Support\Facades\DB;
use Exception;

class ReceivableService
{
    public function __construct(
        protected JournalEntryService $journalService
    ) {}

    /**
     * Invocado únicamente desde SaleService u otros procesos de origen
     */
    public function createReceivable(array $data): Receivable
    {
        return DB::transaction(function () use ($data) {
            $client = Client::findOrFail($data['client_id']);
            
            $receivableAccountId = $client->accounting_account_id 
                ?? $data['accounting_account_id'] 
                ?? $this->getAccountIdByCode('1.1.02');

            $entry = $this->journalService->create([
                'entry_date'  => $data['emission_date'],
                'reference'   => $data['document_number'],
                'description' => "Registro CxC: {$data['document_number']} - Cliente: {$client->name}",
                'status'      => JournalEntry::STATUS_POSTED,
                'items'       => [
                    [
                        'accounting_account_id' => $receivableAccountId,
                        'debit'  => $data['total_amount'],
                        'credit' => 0,
                        'note'   => "Cargo de deuda"
                    ],
                    [
                        'accounting_account_id' => $this->getAccountIdByCode('4.1'),
                        'debit'  => 0,
                        'credit' => $data['total_amount'],
                        'note'   => "Contrapartida de ingreso"
                    ]
                ]
            ]);

            return Receivable::create([
                'client_id'             => $data['client_id'],
                'journal_entry_id'      => $entry->id,
                'accounting_account_id' => $receivableAccountId,
                'document_number'       => $data['document_number'],
                'description'           => $data['description'] ?? "Registro CxC: {$data['document_number']} - Cliente: {$client->name}",
                'total_amount'          => $data['total_amount'],
                'current_balance'       => $data['total_amount'],
                'emission_date'         => $data['emission_date'],
                'due_date'              => $data['due_date'],
                'reference_type'        => $data['reference_type'],
                'reference_id'          => $data['reference_id'],
                'status'                => Receivable::STATUS_UNPAID,
            ]);
        });
    }

    /**
     * Anula una cuenta por cobrar (Solo si no tiene abonos)
     */
    public function cancelReceivable(Receivable $receivable): bool
    {
        return DB::transaction(function () use ($receivable) {
            if ($receivable->status === Receivable::STATUS_CANCELLED) return true;

            if ($receivable->current_balance < $receivable->total_amount) {
                throw new Exception("No se puede anular una factura con abonos.");
            }
            
            return $receivable->update([
                'status' => Receivable::STATUS_CANCELLED,
                'current_balance' => 0
            ]);
        });
    }

    /**
     * ACTUALIZA EL ESTADO BASADO EN EL SALDO
     * Este método es REQUERIDO por PaymentService al registrar abonos.
     */
    public function updateStatusBasedOnBalance(Receivable $receivable): void
    {
        if ($receivable->current_balance <= 0) {
            $receivable->status = Receivable::STATUS_PAID;
        } elseif ($receivable->current_balance < $receivable->total_amount) {
            $receivable->status = Receivable::STATUS_PARTIAL;
        } else {
            $receivable->status = Receivable::STATUS_UNPAID;
        }
        
        $receivable->save();
    }

    protected function getAccountIdByCode(string $code): int
    {
        return AccountingAccount::where('code', $code)->firstOrFail()->id;
    }
}