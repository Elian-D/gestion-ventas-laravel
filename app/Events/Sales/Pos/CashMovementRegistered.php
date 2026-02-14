<?php

namespace App\Events\Sales\Pos;

use App\Models\Sales\Pos\PosCashMovement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CashMovementRegistered
{
    use Dispatchable, SerializesModels;

    // Guardamos el movimiento para que el Listener lo pueda leer
    public function __construct(public PosCashMovement $movement) {}
}