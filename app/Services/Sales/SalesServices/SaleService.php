<?php

namespace App\Services\Sales\SalesServices;

use App\Models\Sales\Sale;
use App\Models\Accounting\{DocumentType, JournalEntry, AccountingAccount};
use App\Models\Inventory\InventoryMovement;
use App\Services\Inventory\InventoryMovementService;
use App\Services\Accounting\JournalEntries\JournalEntryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class SaleService
{
    public function __construct(
        protected InventoryMovementService $inventoryService,
        protected JournalEntryService $journalService
    ) {}

    /**
     * Registra una venta completa: Inventario, Contabilidad y Venta.
     */
    public function create(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            // 1. Obtener y actualizar correlativo (DocumentType FAC)
            $docType = DocumentType::where('code', 'FAC')->firstOrFail();
            $saleNumber = $docType->getNextNumberFormatted();
            $docType->increment('current_number');

            // 2. Crear la Cabecera de la Venta
            $sale = Sale::create([
                'document_type_id' => $docType->id,
                'number'           => $saleNumber,
                'client_id'        => $data['client_id'],
                'warehouse_id'     => $data['warehouse_id'],
                'user_id'          => Auth::id(),
                'sale_date'        => $data['sale_date'] ?? now(),
                'total_amount'     => $data['total_amount'],
                //'apply_tax'        => $data['apply_tax'] ?? false,
                'payment_type'     => $data['payment_type'],
                'status'           => Sale::STATUS_COMPLETED,
                'notes'            => $data['notes'] ?? null,
            ]);

            // 3. Procesar Items: Inventario y Detalle de Venta
            foreach ($data['items'] as $item) {
                // Registrar detalle de venta
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal'   => $item['quantity'] * $item['price'],
                ]);

                // Registrar salida de Inventario (Esto dispara costo de ventas automáticamente)
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

            // 4. Generar Asiento Contable de la VENTA (Ingreso)
            $this->generateSaleAccountingEntry($sale);

            return $sale;
        });
    }

    /**
     * Genera el asiento contable del ingreso (No el costo, eso lo hace inventario)
     */
    protected function generateSaleAccountingEntry(Sale $sale)
    {
        // Cuentas necesarias
        $incomeAccount = AccountingAccount::where('code', '4.1')->first(); // Ventas
        $cashAccount = AccountingAccount::where('code', '1.1.01')->first(); // Caja
        
        // Determinar cuenta deudora (Caja o CxC del cliente)
        $debitAccount = ($sale->payment_type === Sale::PAYMENT_CASH) 
            ? $cashAccount 
            : $sale->client->accounting_account;

        if (!$debitAccount || !$incomeAccount) {
            throw new Exception("Configuración contable incompleta para procesar la venta.");
        }

        $this->journalService->create([
            'entry_date'  => $sale->sale_date,
            'reference'   => $sale->number,
            'description' => "Venta de mercadería - Cliente: {$sale->client->name}",
            'status'      => JournalEntry::STATUS_POSTED,
            'items' => [
                [
                    'accounting_account_id' => $debitAccount->id,
                    'debit'  => $sale->total_amount,
                    'credit' => 0,
                    'note'   => "Ingreso por venta {$sale->payment_type}"
                ],
                [
                    'accounting_account_id' => $incomeAccount->id,
                    'debit'  => 0,
                    'credit' => $sale->total_amount,
                    'note'   => "Venta registrada"
                ]
            ]
        ]);
    }

    /**
     * Anula una venta: Revierte inventario y genera asiento de reversión contable.
     */
    public function cancel(Sale $sale): bool
    {
        return DB::transaction(function () use ($sale) {
            if ($sale->status === Sale::STATUS_CANCELED) {
                throw new Exception("La venta ya se encuentra anulada.");
            }

            // 1. REVERSIÓN FÍSICA Y DE COSTOS (Inventario)
            foreach ($sale->items as $item) {
                $this->inventoryService->register([
                    'warehouse_id'   => $sale->warehouse_id,
                    'product_id'     => $item->product_id,
                    'quantity'       => $item->quantity, // Cantidad positiva para devolver al stock
                    'type'           => InventoryMovement::TYPE_ADJUSTMENT, // Usamos ajuste para control total
                    'description'    => "Reversión por anulación de venta {$sale->number}",
                    'reference_type' => Sale::class,
                    'reference_id'   => $sale->id,
                ]);
            }

            // 2. REVERSIÓN DE INGRESOS (Contabilidad)
            $this->generateCancellationAccountingEntry($sale);

            // 3. Marcar como anulada
            return $sale->update(['status' => Sale::STATUS_CANCELED]);
        });
    }

    /**
     * Crea el contra-asiento para neutralizar el ingreso de la venta.
     */
    protected function generateCancellationAccountingEntry(Sale $sale)
    {
        $incomeAccount = AccountingAccount::where('code', '4.1')->first(); // Ventas
        $cashAccount = AccountingAccount::where('code', '1.1.01')->first(); // Caja
        
        // Identificar si revertimos contra Caja o contra la CxC del cliente
        $debitAccount = ($sale->payment_type === Sale::PAYMENT_CASH) 
            ? $cashAccount 
            : $sale->client->accounting_account;

        $this->journalService->create([
            'entry_date'  => now(),
            'reference'   => "REV-{$sale->number}",
            'description' => "Anulación de ingreso: Venta {$sale->number}",
            'status'      => JournalEntry::STATUS_POSTED,
            'items' => [
                [
                    'accounting_account_id' => $incomeAccount->id,
                    'debit'  => $sale->total_amount, // Cargamos a Ventas (disminuye ingreso)
                    'credit' => 0,
                    'note'   => "Reversión de ingreso por anulación"
                ],
                [
                    'accounting_account_id' => $debitAccount->id,
                    'debit'  => 0,
                    'credit' => $sale->total_amount, // Abonamos a Caja/CxC (disminuye activo)
                    'note'   => "Salida/Crédito por anulación de venta"
                ]
            ]
        ]);
    }
}