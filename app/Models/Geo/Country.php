<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['name', 'iso2', 'iso3'];

    public function states()
    {
        return $this->hasMany(State::class, 'country_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }
}
