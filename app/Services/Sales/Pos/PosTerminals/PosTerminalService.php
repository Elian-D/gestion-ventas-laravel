<?php

namespace App\Services\Sales\Pos\PosTerminals;

use App\Models\Sales\Pos\PosTerminal;
use Illuminate\Support\Facades\DB;

class PosTerminalService
{
    /**
     * Crear una nueva terminal POS
     */
    public function create(array $data): PosTerminal
    {
        return DB::transaction(function () use ($data) {
            $data['is_mobile'] = $data['is_mobile'] ?? false;
            $data['is_active'] = $data['is_active'] ?? true;
            $data['requires_pin'] = $data['requires_pin'] ?? true; // Valor por defecto

            return PosTerminal::create($data);
        });
    }

    /**
     * Actualizar configuración de la terminal.
     */
    public function update(PosTerminal $terminal, array $data): bool
    {
        return DB::transaction(function () use ($terminal, $data) {
            // Si el PIN viene vacío o nulo, lo eliminamos del array 
            // para que Eloquent ignore el campo y mantenga el PIN actual
            if (empty($data['access_pin'])) {
                unset($data['access_pin']);
            }

            // Normalización básica
            $data['printer_format'] = $data['printer_format'] ?: null;
            $data['default_client_id'] = $data['default_client_id'] ?: null;
            $data['requires_pin'] = isset($data['requires_pin']) ? (bool)$data['requires_pin'] : $terminal->requires_pin;

            return $terminal->update($data);
        });
    }
}