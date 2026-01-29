<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Model;
use App\Models\Geo\Country;

class TaxIdentifierType extends Model
{
    protected $fillable = [
        'country_id',
        'entity_type',
        'code',
        'name',
        'vat_name',
        'regex',
        'example',
        'requires_postal_code',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // Scopes útiles
    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    public function scopeForEntity($query, string $type)
    {
        return $query->whereIn('entity_type', [$type, 'both']);
    }
}
