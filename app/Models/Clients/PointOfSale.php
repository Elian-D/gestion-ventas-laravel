<?php

namespace App\Models\Clients;

use App\Models\Geo\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Clients\BusinessType;

class PointOfSale extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'point_of_sales';

    protected $fillable = [
        'client_id',
        'business_type_id',
        'name',
        'code',
        'state_id',
        'city',
        'address',
        'latitude',
        'longitude',
        'contact_name',
        'contact_phone',
        'notes',
        'active',
    ];

    protected static function booted()
    {
        static::created(function ($pos) {
            // Aseguramos que la relación businessType esté disponible para obtener el prefijo
            // Si no está cargada, la cargamos al vuelo
            $prefix = $pos->businessType ? $pos->businessType->prefix : 'POS';

            $generatedCode = sprintf(
                '%s-%05d',
                strtoupper($prefix),
                $pos->id
            );

            // Usamos updateQuietly para que no se dispare el evento 'updated'
            $pos->updateQuietly([
                'code' => $generatedCode
            ]);

            // Sincronizamos la instancia actual para que getDisplayNameAttribute funcione de inmediato
            $pos->syncOriginal();
        });
    }

    /* ===========================
     |      RELACIONES
     =========================== */

    /**
     * Cliente propietario del punto de venta
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Tipo de negocio (colmado, farmacia, drink, etc.)
     */
    public function businessType(): BelongsTo
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id');
    }

    /**
     * Provincia / Estado geográfico
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    /* ===========================
     |    ACCESSORS
     =========================== */

    /**
     * Nombre visible del PDV para tablas y selects
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->code
            ? "{$this->name} ({$this->code})"
            : $this->name;
    }

    /* ===========================
     |      SCOPES
     =========================== */

    /**
     * Relaciones necesarias para el index AJAX
     */
    public function scopeWithIndexRelations($query)
    {
        return $query->with([
            'client:id,name,commercial_name,tax_id',
            'businessType:id,nombre',
            'state:id,name',
        ]);
    }

    /**
     * Solo puntos de venta activos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
