<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipment_types';

    protected $fillable = [
        'nombre',
        'prefix',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /* ===========================
     |  COMPORTAMIENTO AUTOMÁTICO
     =========================== */


    protected static function booted()
    {
        static::saving(function ($type) {
            if (
                empty($type->prefix) ||
                $type->isDirty('nombre')
            ) {
                $type->prefix = self::makePrefix($type->nombre);
            }
        });
    }

    public static function makePrefix(string $name): string
    {
        return strtoupper(
            substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3)
        );
    }

    /* ===========================
     |  SCOPES
     =========================== */

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeInactivos($query)
    {
        return $query->where('activo', false);
    }

    public function scopeFiltrarPorEstado($query, ?string $estado)
    {
        return match ($estado) {
            'activo' => $query->activos(),
            'inactivo' => $query->inactivos(),
            default => $query,
        };
    }

    /* ===========================
     |  UTILIDAD
     =========================== */

    public function toggleActivo(): void
    {
        $this->activo = ! $this->activo;
        $this->save();
    }
}
