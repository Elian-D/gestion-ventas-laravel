<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Geo\Country;
use App\Models\Geo\State;

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
        'country_id',
        'state_id',
        'currency',
        'currency_name',
        'currency_symbol',
        'timezone',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public static function actual()
    {
        return self::first();
    }
}

