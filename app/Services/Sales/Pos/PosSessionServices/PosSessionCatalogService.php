<?php

namespace App\Services\Sales\Pos\PosSessionServices;

use App\Models\Sales\Pos\PosSession;
use App\Models\Sales\Pos\PosTerminal;
use App\Models\User;

class PosSessionCatalogService
{
    /**
     * Datos para los filtros de la tabla de histórico de sesiones.
     */
    public function getForFilters(): array
    {
        return [
            'terminals' => PosTerminal::select('id', 'name')
                ->orderBy('name')
                ->get(),

            'users' => User::select('id', 'name')
                ->has('posSessions') 
                ->orderBy('name')
                ->get(),

            'statuses' => PosSession::getStatuses(),
        ];
    }

    /**
     * Datos para el formulario de apertura/gestión.
     */
    public function getForForm(): array
    {
        return [
            'available_terminals' => PosTerminal::where('is_active', true)
                ->whereDoesntHave('sessions', function ($query) {
                    $query->where('status', PosSession::STATUS_OPEN);
                })
                ->select('id', 'name', 'warehouse_id')
                ->get(),
        ];
    }
}