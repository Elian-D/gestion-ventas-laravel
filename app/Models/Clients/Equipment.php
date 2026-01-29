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
        if (!$this->equipmentType) {
            return;
        }

        $this->updateQuietly([
            'code' => sprintf(
                '%s-%05d',
                strtoupper($this->equipmentType->prefix),
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

    /**
     * Regenera el código (ADMIN ONLY)
     */
    public function regenerateCode(): void
    {
        $this->generateCode();
    }
}
