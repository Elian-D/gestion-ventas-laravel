<?php

namespace App\Services\Inventory;

use App\Models\Inventory\InventoryMovement;
use App\Models\Inventory\InventoryStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class InventoryMovementService
{
    public function register(array $data): InventoryMovement
    {
        return DB::transaction(function () use ($data) {
            $stock = InventoryStock::firstOrCreate(
                ['warehouse_id' => $data['warehouse_id'], 'product_id' => $data['product_id']],
                ['quantity' => 0, 'min_stock' => 0]
            );

            $previousStock = $stock->quantity;
            $type = $data['type'];
            $rawQty = $data['quantity'];
            $absQty = abs($rawQty); 

            $isNegativeOperation = in_array($type, [
                InventoryMovement::TYPE_OUTPUT, 
                InventoryMovement::TYPE_TRANSFER
            ]);

            // Cálculo de stock
            if ($type === InventoryMovement::TYPE_ADJUSTMENT) {
                $newStockQuantity = $previousStock + $rawQty;
            } else {
                $newStockQuantity = $isNegativeOperation ? $previousStock - $absQty : $previousStock + $absQty;
            }

            if ($newStockQuantity < 0) {
                throw new Exception("Operación inválida: El stock resultante no puede ser negativo.");
            }

            // CREAR EL MOVIMIENTO (Origen)
            $movement = InventoryMovement::create([
                'warehouse_id'    => $data['warehouse_id'],
                'to_warehouse_id' => $data['to_warehouse_id'] ?? null,
                'product_id'      => $data['product_id'],
                'user_id'         => Auth::id(),
                'quantity'        => ($type === InventoryMovement::TYPE_ADJUSTMENT) ? $rawQty : ($isNegativeOperation ? -$absQty : $absQty),
                'type'            => $type,
                'previous_stock'  => $previousStock,
                'current_stock'   => $newStockQuantity,
                'description'     => $data['description'],
                'reference_type'  => $data['reference_type'] ?? null,
                'reference_id'    => $data['reference_id'] ?? null,
            ]);

            $stock->update(['quantity' => $newStockQuantity]);

            // SI ES TRANSFERENCIA, GENERAR EL MOVIMIENTO ESPEJO (Entrada en destino)
            if ($type === InventoryMovement::TYPE_TRANSFER && isset($data['to_warehouse_id'])) {
                $this->registerTransferEntry($movement, $data);
            }

            return $movement;
        });
    }

    private function registerTransferEntry(InventoryMovement $parentMovement, array $data)
    {
        $destStock = InventoryStock::firstOrCreate(
            ['warehouse_id' => $data['to_warehouse_id'], 'product_id' => $data['product_id']],
            ['quantity' => 0, 'min_stock' => 0]
        );

        $prevDestStock = $destStock->quantity;
        $qty = abs($data['quantity']);
        $newDestStock = $prevDestStock + $qty;

        // Crear segundo movimiento explícito para el Kardex
        InventoryMovement::create([
            'warehouse_id'    => $data['to_warehouse_id'],
            'product_id'      => $data['product_id'],
            'user_id'         => Auth::id(),
            'quantity'        => $qty, // Positivo porque es entrada
            'type'            => InventoryMovement::TYPE_TRANSFER,
            'previous_stock'  => $prevDestStock,
            'current_stock'   => $newDestStock,
            'description'     => "Entrada por transferencia desde: " . $parentMovement->warehouse->name . ". Motivo: " . $data['description'],
            'reference_type'  => get_class($parentMovement),
            'reference_id'    => $parentMovement->id, // Vinculamos ambos movimientos
        ]);

        $destStock->update(['quantity' => $newDestStock]);
    }
}