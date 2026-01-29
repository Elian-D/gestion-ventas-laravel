<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipments';

    protected $fillable = [
        'point_of_sale_id',
        'equipment_type_id',
        'serial_number',
        'name',
        'model',
        'notes',
        'active',
        'code',
    ];

    protected static function booted()
    {
        static::created(function (Equipment $equipment) {
            $equipment->generateCode();
        });
    }

    /* ===========================
     |  RELACIONES
     =========================== */

    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class);
    }

    public function pointOfSale()
    {
        return $this->belongsTo(PointOfSale::class);
    }

    /* ===========================
     |  CÓDIGOS
     =========================== */

    public function generateCode(): void
    {
        // Carga la relación si no existe para evitar el error de prefix sobre null
        if (!$this->equipmentType) {
            $this->load('equipmentType');
        }

        $prefix = $this->equipmentType ? $this->equipmentType->prefix : 'EQ';

        $this->updateQuietly([
            'code' => sprintf(
                '%s-%05d',
                strtoupper($prefix),
                $this->id
            )
        ]);
    }

    /* ===========================
     |      SCOPES
     =========================== */

    /**
     * Relaciones necesarias para el index AJAX
     */
    public function scopeWithIndexRelations($query)
    {
        return $query->with([
            'equipmentType:id,nombre,prefix',
            'pointOfSale:id,name,address',
        ]);
    }
}
