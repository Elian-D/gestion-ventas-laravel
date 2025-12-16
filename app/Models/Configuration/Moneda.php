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
    // Descomentar mas tarde si es necesario
/*     public function configuracion()
{
    return $this->hasOne(ConfiguracionGeneral::class);
}
 */
}
