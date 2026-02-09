<?php

namespace App\Services\Sales\SalesServices;

use App\Models\Sales\Sale;
use App\Models\Accounting\{DocumentType, JournalEntry, AccountingAccount, Receivable};
use App\Models\Inventory\InventoryMovement;
use App\Services\Inventory\InventoryMovementService;
use App\Services\Accounting\JournalEntries\JournalEntryService;
use App\Services\Accounting\Receivable\ReceivableService;
use Illuminate\Support\Facades\{DB, Auth};
use App\Services\Sales\InvoicesServices\InvoiceService; 
use Exception;

class SaleService
{
    public function __construct(
        protected InventoryMovementService $inventoryService,
        protected JournalEntryService $journalService,
        protected ReceivableService $receivableService,
        protected InvoiceService $invoiceService
    ) {}

    public function create(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $docType = DocumentType::where('code', 'FAC')->firstOrFail();
            $saleNumber = $docType->getNextNumberFormatted();
            $docType->increment('current_number');

            $sale = Sale::create([
                'document_type_id' => $docType->id,
                'number'           => $saleNumber,
                'client_id'        => $data['client_id'],
                'warehouse_id'     => $data['warehouse_id'],
                'user_id'          => Auth::id(),
                'sale_date'        => $data['sale_date'] ?? now(),
                'total_amount'     => $data['total_amount'],
                'payment_type'     => $data['payment_type'],
                // --- NUEVOS CAMPOS AQUÍ ---
                'cash_received'    => $data['payment_type'] === Sale::PAYMENT_CASH ? ($data['cash_received'] ?? 0) : 0,
                'cash_change'      => $data['payment_type'] === Sale::PAYMENT_CASH ? ($data['cash_change'] ?? 0) : 0,
                // --------------------------
                'status'           => Sale::STATUS_COMPLETED,
                'notes'            => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal'   => $item['quantity'] * $item['price'],
                ]);

                $this->inventoryService->register([
                    'warehouse_id'   => $data['warehouse_id'],
                    'product_id'     => $item['product_id'],
                    'quantity'       => $item['quantity'],
                    'type'           => InventoryMovement::TYPE_OUTPUT,
                    'description'    => "Venta {$saleNumber}",
                    'reference_type' => Sale::class,
                    'reference_id'   => $sale->id,
                ]);
            }

            if ($sale->payment_type === Sale::PAYMENT_CASH) {
                $this->generateSaleAccountingEntry($sale);
            } else {
                $this->receivableService->createReceivable([
                    'client_id'       => $sale->client_id,
                    'total_amount'    => $sale->total_amount,
                    'emission_date'   => $sale->sale_date,
                    'due_date'        => $sale->sale_date->copy()->addDays(30),
                    'document_number' => $sale->number,
                    'reference_type'  => Sale::class,
                    'reference_id'    => $sale->id,
                    'description'     => "Venta a crédito registrada desde POS"
                ]);
            }

            $this->invoiceService->createFromSale($sale);
            return $sale;
        });
    }

    public function cancel(Sale $sale): bool
    {
        return DB::transaction(function () use ($sale) {
            if ($sale->status === Sale::STATUS_CANCELED) {
                throw new Exception("La venta ya se encuentra anulada.");
            }

            // 1. Manejo de la reversión financiera (CxC)
            if ($sale->payment_type === Sale::PAYMENT_CREDIT) {
                $receivable = Receivable::where('reference_type', Sale::class)
                    ->where('reference_id', $sale->id)
                    ->first();

                if ($receivable) {
                    if ($receivable->current_balance < $receivable->total_amount || $receivable->status === Receivable::STATUS_PAID) {
                        throw new Exception("No se puede anular: El cliente ya tiene abonos.");
                    }
                    $this->receivableService->cancelReceivable($receivable);
                }
            }

            // 2. Reversión de Ingresos (4.1 vs Caja/CxC)
            $this->generateCancellationAccountingEntry($sale);

            // 3. REVERSIÓN DE COSTO Y STOCK (Aquí estaba el error)
            foreach ($sale->items as $item) {
                $this->inventoryService->register([
                    'warehouse_id'   => $sale->warehouse_id,
                    'product_id'     => $item->product_id,
                    'quantity'       => $item->quantity, // Cantidad positiva para reingreso
                    'type'           => InventoryMovement::TYPE_ADJUSTMENT, // <--- CAMBIO CLAVE
                    'description'    => "Reversión de costo por anulación {$sale->number}",
                    'reference_type' => Sale::class,
                    'reference_id'   => $sale->id,
                ]);
            }

            $this->invoiceService->cancelInvoice($sale);
            
            return $sale->update(['status' => Sale::STATUS_CANCELED]);
        });
    }

    protected function generateSaleAccountingEntry(Sale $sale)
    {
        $incomeAccount = AccountingAccount::where('code', '4.1')->first(); 
        $cashAccount = AccountingAccount::where('code', '1.1.01')->first(); 
        
        if (!$cashAccount || !$incomeAccount) {
            throw new Exception("Configuración contable no encontrada.");
        }

        $this->journalService->create([
            'entry_date'  => $sale->sale_date,
            'reference'   => $sale->number,
            'description' => "Venta Contado - Cliente: {$sale->client->name}",
            'status'      => JournalEntry::STATUS_POSTED,
            'items' => [
                ['accounting_account_id' => $cashAccount->id, 'debit' => $sale->total_amount, 'credit' => 0],
                ['accounting_account_id' => $incomeAccount->id, 'debit' => 0, 'credit' => $sale->total_amount]
            ]
        ]);
    }

    protected function generateCancellationAccountingEntry(Sale $sale)
    {
        $incomeAccount = AccountingAccount::where('code', '4.1')->first();
        
        if ($sale->payment_type === Sale::PAYMENT_CASH) {
            $contraAccount = AccountingAccount::where('code', '1.1.01')->first();
        } else {
            $contraAccount = $sale->client->accountingAccount ?? AccountingAccount::where('code', '1.1.02')->first();
        }

        $this->journalService->create([
            'entry_date'  => now(),
            'reference'   => "REV-{$sale->number}",
            'description' => "Anulación Venta {$sale->number}",
            'status'      => JournalEntry::STATUS_POSTED,
            'items' => [
                ['accounting_account_id' => $incomeAccount->id, 'debit' => $sale->total_amount, 'credit' => 0],
                ['accounting_account_id' => $contraAccount->id, 'debit' => 0, 'credit' => $sale->total_amount]
            ]
        ]);
    }
}