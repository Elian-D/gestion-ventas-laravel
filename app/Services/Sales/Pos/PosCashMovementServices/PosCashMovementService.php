<?php

namespace App\Services\Sales\Pos\PosCashMovementServices;

use App\Models\Sales\Pos\PosCashMovement;
use App\Models\Sales\Pos\PosSession;
use App\Events\Sales\Pos\CashMovementRegistered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class PosCashMovementService
{
    /**
     * Registra un movimiento de caja y dispara el evento para el asiento.
     */
    public function store(array $data): PosCashMovement
    {
        return DB::transaction(function () use ($data) {
            // 1. Validar Sesión
            $session = PosSession::findOrFail($data['pos_session_id']);
            
            if (!$session->isOpen()) {
                throw new Exception("No se pueden registrar movimientos en una caja cerrada.");
            }

            // 2. Crear el registro del movimiento
            $movement = PosCashMovement::create([
                'pos_session_id' => $session->id,
                'user_id'        => Auth::id(),
                'type'           => $data['type'],
                'amount'         => $data['amount'],
                'reason'         => $data['reason'],
                'reference'      => $data['reference'] ?? null,
                'metadata'       => $data['metadata'] ?? null,
            ]);

            // 3. DISPARAR EVENTO (Toda la lógica contable vive ahora en el Listener)
            event(new CashMovementRegistered($movement));

            return $movement;
        });
    }
}