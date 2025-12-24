<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model
{
    use HasFactory;

    const TIPO_PORCENTAJE = 'porcentaje';
    const TIPO_FIJO = 'fijo';

    protected $fillable = [
        'nombre',
        'tipo',
        'valor',
        'es_incluido',
    ];

    // Impuesto.php
    public function configuracion()
    {
        return $this->hasOne(ConfiguracionGeneral::class);
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

}
