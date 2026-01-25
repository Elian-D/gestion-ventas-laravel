<?php

namespace App\Services\Client;

use App\Models\Clients\Client;
use Illuminate\Support\Facades\DB;

class ClientService
{

    public function createClient(array $data): Client
    {
        return Client::create($data);
    }

    public function performBulkAction(array $ids, string $action, $value = null): int
    {
        return DB::transaction(function () use ($ids, $action, $value) {
            $query = Client::whereIn('id', $ids);
            $count = count($ids);

            match ($action) {
                'delete'           => $query->delete(),
                'change_status'    => $query->update(['estado_cliente_id' => $value]),
                'change_geo_state' => $query->update(['state_id' => $value]),
                default => throw new \InvalidArgumentException("Acción no soportada"),
            };

            return $count;
        });
    }

    public function getActionLabel(string $action): string
    {
        return match ($action) {
            'delete'           => 'eliminado',
            'change_status'    => 'actualizado el estado',
            'change_geo_state' => 'actualizado la ubicación',
            default            => 'procesado',
        };
    }
}