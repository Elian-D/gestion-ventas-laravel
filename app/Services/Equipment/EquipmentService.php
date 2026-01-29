<?php

namespace App\Services\Equipment;

use App\Models\Clients\Equipment;
use App\Models\Clients\EquipmentType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EquipmentService
{

    public function create(array $data): Equipment
    {
        return DB::transaction(function () use ($data) {
            // El evento booted -> created del modelo llamará a generateCode automáticamente
            return Equipment::create($data);
        });
    }

    public function update(Equipment $equipment, array $data): bool
    {
        return DB::transaction(function () use ($equipment, $data) {
            $oldTypeId = $equipment->equipment_type_id;
            
            $updated = $equipment->update($data);

            // Si cambió el tipo o se marcó el checkbox de regenerar
            if ($updated) {
                $typeChanged = isset($data['equipment_type_id']) && $data['equipment_type_id'] != $oldTypeId;
                $forceRegenerate = isset($data['regenerate_code']) && $data['regenerate_code'] == '1';

                if ($typeChanged || $forceRegenerate) {
                    $equipment->generateCode();
                }
            }

            return $updated;
        });
    }

    /**
     * Acciones masivas
     */
    public function performBulkAction(array $ids, string $action, $value = null): int
    {
        return DB::transaction(function () use ($ids, $action, $value) {

            $query = Equipment::whereIn('id', $ids);
            $count = count($ids);

            match ($action) {
                'delete'          => $query->delete(),
                'change_active'   => $query->update(['active' => $value]),
                'change_type'     => $query->update(['equipment_type_id' => $value]),
                'change_pos'      => $query->update(['point_of_sale_id' => $value]),
                default           => throw new \InvalidArgumentException('Acción no soportada'),
            };

            return $count;
        });
    }

    /**
     * Etiquetas humanas para mensajes flash
     */
    public function getActionLabel(string $action): string
    {
        return match ($action) {
            'delete'        => 'eliminado',
            'change_active' => 'actualizado el estado',
            'change_type'   => 'actualizado el tipo de equipo',
            'change_pos'    => 'actualizado el punto de venta',
            default         => 'procesado',
        };
    }

}
