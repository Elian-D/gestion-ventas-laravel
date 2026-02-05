<?php

namespace App\Services\Inventory\WarehouseService;

use App\Models\Inventory\Warehouse;
use Illuminate\Support\Facades\DB;
use Exception;

class WarehouseService
{
    public function store(array $data): Warehouse
    {
        return DB::transaction(function () use ($data) {
            return Warehouse::create($data);
            // Nota: El modelo booted() ya se encarga de crear la cuenta y el código
        });
    }

    public function update(Warehouse $warehouse, array $data): Warehouse
    {
        return DB::transaction(function () use ($warehouse, $data) {
            $nuevoEstado = $data['is_active'] ?? $warehouse->is_active;

            // Protección de último almacén activo
            if ($warehouse->is_active && !$nuevoEstado) {
                if (Warehouse::where('is_active', true)->count() <= 1) {
                    throw new Exception('No se puede desactivar el único almacén activo del sistema.');
                }
            }

            $nombreAnterior = $warehouse->name;
            $warehouse->update($data);

            if ($nombreAnterior !== $warehouse->name) {
                $warehouse->generateCode();
                // Opcional: Actualizar el nombre de la cuenta contable
                if ($warehouse->accountingAccount) {
                    $warehouse->accountingAccount->update([
                        'name' => 'Inventario: ' . $warehouse->name
                    ]);
                }
            }

            return $warehouse;
        });
    }

    public function toggle(Warehouse $warehouse): bool
    {
        if ($warehouse->is_active && Warehouse::where('is_active', true)->count() <= 1) {
            throw new Exception('Debe haber al menos un almacén activo.');
        }

        return (bool) $warehouse->toggleActivo();
    }
}