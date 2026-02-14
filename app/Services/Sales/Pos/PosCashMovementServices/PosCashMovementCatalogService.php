<?php

namespace App\Services\Sales\Pos\PosCashMovementServices;

use App\Models\User;
use App\Models\Sales\Pos\PosSession;
use App\Models\Sales\Pos\PosCashMovement;
use Illuminate\Support\Facades\Auth;

class PosCashMovementCatalogService
{

    public function getForFilters(): array
    {
        return [
            'users' => User::query()
                ->cashiers()
                ->orWhereHas('posCashMovements')
                ->get(['id', 'name']),

            // QUITAMOS EL MAP. Dejamos que la colección de objetos fluya a la vista.
            'sessions' => PosSession::with('terminal')
                ->latest()
                ->take(30)
                ->get(),

            'types' => [
                PosCashMovement::TYPE_IN  => 'Entrada de Efectivo',
                PosCashMovement::TYPE_OUT => 'Salida de Efectivo',
            ],
        ];
    }

    public function getForForm(): array
    {
        return $this->getForFilters();
    }
}