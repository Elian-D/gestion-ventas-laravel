<?php

namespace App\DTOs\Sales;

/**
 * Contenedor de datos para transportar el contexto del POS 
 * hacia el motor de ventas (SaleService).
 */
class PosContext
{
    public function __construct(
        public int $terminal_id,
        public int $session_id,
        public int $cash_account_id, // Cuenta contable de la caja de la terminal
        public int $warehouse_id,    // Almacén vinculado a la terminal
    ) {}

    /**
     * Opcional: Un método estático para crearlo desde el modelo de Sesión directamente
     */
    public static function fromSession($session): self
    {
        return new self(
            terminal_id: $session->pos_terminal_id,
            session_id: $session->id,
            cash_account_id: $session->terminal->cash_account_id,
            warehouse_id: $session->terminal->warehouse_id
        );
    }
}