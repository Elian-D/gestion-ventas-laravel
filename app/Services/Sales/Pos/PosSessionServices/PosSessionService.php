<?php

namespace App\Services\Sales\Pos\PosSessionServices;

use App\Models\Accounting\AccountingAccount;
use App\Models\Accounting\JournalEntry;
use App\Models\Sales\Pos\PosSession;
use App\Services\Accounting\JournalEntries\JournalEntryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PosSessionService
{

    public function __construct(protected JournalEntryService $journalService) {}
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
     * Calcula el monto que DEBERÍA haber en caja.
     */
    public function calculateExpected(PosSession $session): float
    {
        $opening = $session->opening_balance;
        
        // 1. Ventas marcadas como CONTADO (CASH) en esta sesión
        // Usamos el campo payment_type que ya manejas en el modelo Sale
        $cashSales = $session->sales()
            ->where('status', \App\Models\Sales\Sale::STATUS_COMPLETED)
            ->where('payment_type', \App\Models\Sales\Sale::PAYMENT_CASH)
            ->sum('total_amount');

        // 2. Movimientos manuales (Entradas y Salidas de efectivo)
        $inflows = $session->cashMovements()->in()->sum('amount');
        $outflows = $session->cashMovements()->out()->sum('amount');

        // Formula: Inicial + Ventas Efectivo + Entradas Manuales - Salidas Manuales
        return (float) ($opening + $cashSales + $inflows) - $outflows;
    }

    /**
     * Cerrar la sesión con Arqueo Automático.
     */
    public function close(PosSession $session, array $data): bool
    {
        return DB::transaction(function () use ($session, $data) {
            if (!$session->isOpen()) {
                throw new \Exception("La sesión ya se encuentra cerrada.");
            }

            // 1. Calculamos el esperado "la verdad del sistema"
            $expected = $this->calculateExpected($session);
            $real = $data['closing_balance'];
            $difference = $real - $expected;

            // 2. Persistimos la auditoría
            $session->update([
                'closed_at'        => now(),
                'expected_balance' => $expected, // Grabamos lo que debió haber
                'closing_balance'  => $real,     // Lo que el cajero contó
                'difference'       => $difference, // El descuadre
                'status'           => PosSession::STATUS_CLOSED,
                'notes'            => $data['notes'] ?? $session->notes,
            ]);

            // 3. Solo si hay descuadre real, generamos el asiento de ajuste
            if (abs($difference) >= 0.01) {
                $this->createAdjustmentEntry($session, $difference);
            }

            return true;
        });
    }

    protected function createAdjustmentEntry(PosSession $session, float $difference): void
    {
        $terminalAccount = $session->terminal->cash_account_id 
            ?? AccountingAccount::where('code', '1.1.01')->value('id');

        if ($difference > 0) {
            // SOBRANTE: Débito Caja / Crédito Ingresos Extraordinarios
            $contraAccount = AccountingAccount::where('code', '4.2.01')->value('id');
            $items = [
                ['accounting_account_id' => $terminalAccount, 'debit' => $difference, 'credit' => 0],
                ['accounting_account_id' => $contraAccount, 'debit' => 0, 'credit' => $difference],
            ];
            $type = "Sobrante";
        } else {
            // FALTANTE: Débito Gastos (Faltante) / Crédito Caja
            $contraAccount = AccountingAccount::where('code', '5.3.04')->value('id');
            $items = [
                ['accounting_account_id' => $contraAccount, 'debit' => abs($difference), 'credit' => 0],
                ['accounting_account_id' => $terminalAccount, 'debit' => 0, 'credit' => abs($difference)],
            ];
            $type = "Faltante";
        }

        $this->journalService->create([
            'entry_date'  => now(),
            'reference'   => "POS-ADJ-{$session->id}",
            'description' => "Ajuste de arqueo ($type) - Sesión #{$session->id}",
            'status'      => JournalEntry::STATUS_POSTED,
            'items'       => $items
        ]);
    }
    /**
     * Actualizar notas o datos menores sin cambiar el flujo de estado.
     */
    public function update(PosSession $session, array $data): bool
    {
        return $session->update($data);
    }
}

