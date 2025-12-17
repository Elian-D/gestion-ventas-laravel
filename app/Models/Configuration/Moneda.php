<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'simbolo',
        'decimales',
        'es_principal',
    ];

    // Relación con ConfiguracionGeneral
    public function configuraciones()
    {
        return $this->hasMany(ConfiguracionGeneral::class);
    }
}
