<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionGeneral extends Model
{
    use HasFactory;

    protected $table = 'configuraciones_generales';

    protected $fillable = [
        'nombre_empresa',
        'logo',
        'telefono',
        'email',
        'direccion',
        'ciudad',
        'pais',
        'moneda_id',
        'impuesto_id',
        'timezone',
    ];

    public function moneda()
{
    return $this->belongsTo(Moneda::class);
}

public function impuesto()
{
    return $this->belongsTo(Impuesto::class);
}

public static function actual()
{
    return self::first();
}

}
