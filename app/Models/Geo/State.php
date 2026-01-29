<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }
}
