<?php

namespace App\Services\Sales\Pos\PosSessionServices;

use App\Models\Sales\Pos\PosSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PosSessionService
{
    /**
     * Abrir una nueva sesión de caja.
     */
    public function open(array $data): PosSession
    {
        return DB::transaction(function () use ($data) {
            $terminalId = $data['terminal_id'];
            $userId = Auth::id();

            // 1. Validar que la terminal no tenga una sesión abierta
            $activeTerminalSession = PosSession::where('terminal_id', $terminalId)
                ->open()
                ->exists();

            if ($activeTerminalSession) {
                throw ValidationException::withMessages([
                    'terminal_id' => 'Esta terminal ya tiene una sesión activa.'
                ]);
            }

            // 2. Validar que el usuario no tenga OTRA sesión abierta en otra terminal
            $activeUserSession = PosSession::where('user_id', $userId)
                ->open()
                ->exists();

            if ($activeUserSession) {
                throw ValidationException::withMessages([
                    'user_id' => 'Ya tienes una sesión abierta en otra terminal. Ciérrala antes de abrir una nueva.'
                ]);
            }

            // 3. Crear la sesión
            return PosSession::create([
                'terminal_id'     => $terminalId,
                'user_id'         => $userId,
                'opened_at'       => now(),
                'opening_balance' => $data['opening_balance'] ?? 0,
                'status'          => PosSession::STATUS_OPEN,
                'notes'           => $data['notes'] ?? null,
            ]);
        });
    }

    /**
     * Cerrar la sesión actual (Liquidación).
     */
    public function close(PosSession $session, array $data): bool
    {
        return DB::transaction(function () use ($session, $data) {
            if (!$session->isOpen()) {
                throw new \Exception("La sesión ya se encuentra cerrada.");
            }

            // Aquí en el futuro sumaremos ventas y movimientos de cash_movements
            // Por ahora, cerramos con el balance proporcionado.
            
            return $session->update([
                'closed_at'       => now(),
                'closing_balance' => $data['closing_balance'],
                'status'          => PosSession::STATUS_CLOSED,
                'notes'           => $data['notes'] ?? $session->notes,
            ]);
        });
    }
    
    /**
     * Actualizar notas o datos menores sin cambiar el flujo de estado.
     */
    public function update(PosSession $session, array $data): bool
    {
        return $session->update($data);
    }
}