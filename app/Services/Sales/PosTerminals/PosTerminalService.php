<?php

namespace App\Services\Sales\PosTerminals;

use App\Models\Sales\Pos\PosTerminal;
use Illuminate\Support\Facades\DB;

class PosTerminalService
{
    /**
     * Crear una nueva terminal POS dentro de una transacción.
     */
    public function create(array $data): PosTerminal
    {
        return DB::transaction(function () use ($data) {
            // Aseguramos valores por defecto para booleanos si no vienen en el request
            $data['is_mobile'] = $data['is_mobile'] ?? false;
            $data['is_active'] = $data['is_active'] ?? true;

            return PosTerminal::create($data);
        });
    }

    /**
     * Actualizar configuración de la terminal.
     */
    public function update(PosTerminal $terminal, array $data): bool
    {
        return DB::transaction(function () use ($terminal, $data) {
            return $terminal->update($data);
        });
    }
}