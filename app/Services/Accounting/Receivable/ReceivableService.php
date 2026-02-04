<?php

namespace App\Services\Accounting\Receivable;

use App\Models\Accounting\AccountingAccount;
use App\Models\Accounting\Receivable;
use App\Models\Accounting\JournalEntry;
use App\Models\Clients\Client;
use App\Services\Accounting\JournalEntries\JournalEntryService;
use Illuminate\Support\Facades\DB;

class ReceivableService
{
    public function __construct(
        protected JournalEntryService $journalService
    ) {}

    public function createReceivable(array $data): Receivable
    {
        return DB::transaction(function () use ($data) {
            $client = Client::findOrFail($data['client_id']);
            
            // 1. Determinar Cuenta de CxC (Cliente o General 1.1.02)
            $receivableAccountId = $client->accounting_account_id 
                ?? $data['accounting_account_id'] 
                ?? $this->getAccountIdByCode('1.1.02');

            // 2. Crear Asiento Contable de Origen
            $entry = $this->journalService->create([
                'entry_date'  => $data['emission_date'],
                'reference'   => $data['document_number'],
                'description' => "Registro CxC: {$data['document_number']} - Cliente: {$client->name}",
                'status'      => JournalEntry::STATUS_POSTED,
                'items'       => [
                    [
                        // DEBITO: Aumenta el Activo (CxC)
                        'accounting_account_id' => $receivableAccountId,
                        'debit'  => $data['total_amount'],
                        'credit' => 0,
                        'note'   => "Cargo de deuda"
                    ],
                    [
                        // CREDITO: Ingreso por Ventas (4.1)
                        'accounting_account_id' => $this->getAccountIdByCode('4.1'),
                        'debit'  => 0,
                        'credit' => $data['total_amount'],
                        'note'   => "Contrapartida de ingreso"
                    ]
                ]
            ]);

            // 3. Crear la CxC vinculada al asiento
            $data['current_balance'] = $data['total_amount'];
            $data['status'] = $data['status'] ?? Receivable::STATUS_UNPAID;
            $data['journal_entry_id'] = $entry->id;
            $data['accounting_account_id'] = $receivableAccountId;

            return Receivable::create($data);
        });
    }

    protected function getDefaultReceivableAccountId(): int
    {
        // Buscamos la cuenta 1.1.02 configurada en el catálogo
        return AccountingAccount::where('code', '1.1.02')->first()->id;
    }

    /**
     * Anula una cuenta por cobrar
     * Solo si no tiene abonos procesados (current_balance == total_amount)
     */
    public function cancelReceivable(Receivable $receivable): bool
    {
        return DB::transaction(function () use ($receivable) {
            if ($receivable->current_balance < $receivable->total_amount) {
                throw new \Exception("No se puede anular una factura que ya tiene abonos registrados.");
            }

            $receivable->update(['status' => Receivable::STATUS_CANCELLED]);
            return $receivable->delete(); // SoftDelete
        });
    }

    public function updateReceivable(Receivable $receivable, array $data): Receivable
    {
        return DB::transaction(function () use ($receivable, $data) {
            $oldAmount = $receivable->total_amount;
            $newAmount = $data['total_amount'];

            // 1. Si el monto cambió, ajustamos la contabilidad
            if ($oldAmount != $newAmount) {
                $difference = $newAmount - $oldAmount;

                $this->journalService->create([
                    'entry_date'  => now(),
                    'reference'   => "ADJ-{$receivable->document_number}",
                    'description' => "Ajuste de monto en CxC: {$receivable->document_number}. De {$oldAmount} a {$newAmount}",
                    'status'      => JournalEntry::STATUS_POSTED,
                    'items'       => [
                        [
                            // Si difference es +, es un Débito (aumenta deuda). Si es -, es Crédito (baja deuda).
                            'accounting_account_id' => $receivable->accounting_account_id,
                            'debit'  => $difference > 0 ? abs($difference) : 0,
                            'credit' => $difference < 0 ? abs($difference) : 0,
                            'note'   => "Ajuste de saldo por edición"
                        ],
                        [
                            // Contrapartida en Ventas/Ingresos
                            'accounting_account_id' => $this->getAccountIdByCode('4.1'),
                            'debit'  => $difference < 0 ? abs($difference) : 0,
                            'credit' => $difference > 0 ? abs($difference) : 0,
                            'note'   => "Ajuste de ingreso por edición"
                        ]
                    ]
                ]);
            }

            // 2. Lógica de saldos que ya tenías
            $amountPaid = $receivable->total_amount - $receivable->current_balance;
            $data['current_balance'] = $newAmount - $amountPaid;

            $receivable->update($data);
            $this->updateStatusBasedOnBalance($receivable);

            return $receivable;
        });
    }

public function registerPayment(Receivable $receivable, array $data): Receivable
    {
        return DB::transaction(function () use ($receivable, $data) {
            $amount = $data['payment_amount'];

            if ($amount > $receivable->current_balance) {
                throw new \Exception("El abono no puede ser mayor al saldo pendiente.");
            }

            // 1. Crear Asiento Contable del Abono
            $this->journalService->create([
                'entry_date'  => $data['payment_date'] ?? now(),
                'reference'   => "ABONO-" . $receivable->document_number,
                'description' => "Abono recibido de {$receivable->client->name}. Ref: {$receivable->document_number}",
                'status'      => JournalEntry::STATUS_POSTED,
                'items'       => [
                    [
                        // DEBITO: Entra dinero a Caja (1.1.01)
                        'accounting_account_id' => $this->getAccountIdByCode('1.1.01'),
                        'debit'  => $amount,
                        'credit' => 0,
                        'note'   => "Ingreso de efectivo/banco"
                    ],
                    [
                        // CREDITO: Disminuye el Activo (CxC)
                        'accounting_account_id' => $receivable->accounting_account_id,
                        'debit'  => 0,
                        'credit' => $amount,
                        'note'   => "Reducción de saldo pendiente"
                    ]
                ]
            ]);

            // 2. Actualizar saldos del modelo
            $receivable->current_balance -= $amount;
            $this->updateStatusBasedOnBalance($receivable);
            $receivable->client->refreshBalance();

            return $receivable;
        });
    }

    protected function getAccountIdByCode(string $code): int
    {
        return AccountingAccount::where('code', $code)->firstOrFail()->id;
    }

    /**
     * Lógica para actualizar el estado basado en el saldo
     */
    public function updateStatusBasedOnBalance(Receivable $receivable): void
    {
        // Usamos quietly() para evitar bucles infinitos si el observer escucha 'updated'
        // aunque en este caso el observer escucha 'saved', lo cual es seguro.
        if ($receivable->current_balance <= 0) {
            $receivable->status = Receivable::STATUS_PAID;
        } elseif ($receivable->current_balance < $receivable->total_amount) {
            $receivable->status = Receivable::STATUS_PARTIAL;
        } else {
            $receivable->status = Receivable::STATUS_UNPAID;
        }
        
        $receivable->save();
    }
}