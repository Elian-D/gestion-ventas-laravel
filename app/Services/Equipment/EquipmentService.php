<?php

namespace App\Services\Equipment;

use App\Models\Clients\Equipment;
use App\Models\Clients\EquipmentType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EquipmentService
{
    /**
     * Crear un equipo
     */
    public function create(array $data): Equipment
    {
        return DB::transaction(function () use ($data) {

            $equipment = Equipment::create($data);

            // Generar código solo si no viene forzado
            if (empty($equipment->code)) {
                $equipment->code = $this->generateCode($equipment);
                $equipment->save();
            }

            return $equipment;
        });
    }

    /**
     * Actualizar un equipo
     */
    public function update(Equipment $equipment, array $data): bool
    {
        return DB::transaction(function () use ($equipment, $data) {

            $equipment->update($data);

            // Si cambió el tipo de equipo, se regenera el código
            if (array_key_exists('equipment_type_id', $data)) {
                $equipment->code = $this->generateCode($equipment);
                $equipment->save();
            }

            return true;
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

    /**
     * Generar código del equipo
     * Ejemplo: FRZ-8F3A2
     */
    protected function generateCode(Equipment $equipment): string
    {
        $type = EquipmentType::findOrFail($equipment->equipment_type_id);

        return sprintf(
            '%s-%s',
            Str::upper($type->prefix),
            Str::upper(Str::random(5))
        );
    }
}
