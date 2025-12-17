<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Impuesto extends Model
{
    use HasFactory, SoftDeletes;

    const TIPO_PORCENTAJE = 'porcentaje';
    const TIPO_FIJO = 'fijo';

    protected $fillable = [
        'nombre',
        'tipo',
        'valor',
        'es_incluido',
        'estado',
    ];

    // Relación con ConfiguracionGeneral
    public function configuraciones()
    {
        return $this->hasMany(ConfiguracionGeneral::class);
    }

    // Métodos para verificar el tipo de impuesto
    public function isPorcentaje(): bool
    {
        return $this->tipo === self::TIPO_PORCENTAJE;
    }

    public function isFijo(): bool
    {
        return $this->tipo === self::TIPO_FIJO;
    }


    // Scopes para filtrar por estado
    public function scopeActivo($query)
    {
        return $query->where('estado', true);
    }

    public function scopeInactivo($query)
    {
        return $query->where('estado', false);
    }

    // Alternar estado
    public function toggleEstado(): void
    {
        $this->estado = ! $this->estado;
        $this->save();
    }
}
