<?php

namespace App\Models\Clients;

use App\Models\Configuration\EstadosCliente;
use App\Models\Geo\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Configuration\ConfiguracionGeneral;
use App\Models\Configuration\TaxIdentifierType;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'estado_cliente_id',
        'name',
        'commercial_name',
        'email',
        'phone',
        'state_id',
        'city',
        'tax_identifier_type_id',
        'tax_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /* ===========================
     |      RELACIONES
     =========================== */

    /**
     * Obtiene el estado operativo del cliente (Moroso, Activo, etc.)
     */
    public function estadoCliente(): BelongsTo
    {
        return $this->belongsTo(EstadosCliente::class, 'estado_cliente_id');
    }

    /**
     * Obtiene la provincia/estado geográfico
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    /* ===========================
     |    SCOPES (FILTROS)
     =========================== */

    public function scopeActivos($query)
    {
        return $query->where('active', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('commercial_name', 'like', "%{$term}%")
              ->orWhere('tax_id', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    /* ===========================
     |    ACCESSORS (AYUDANTES DE VISTA)
     =========================== */

    /**
     * Devuelve el nombre comercial si existe, de lo contrario el legal.
     * Útil para listados rápidos.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->commercial_name ?: $this->name;
    }

    /**
     * Atajo para saber si el cliente puede realizar operaciones financieras
     */
    public function getPuedeOperarAttribute(): bool
    {
        return $this->active && $this->estadoCliente->puedeOperar();
    }

    /**
     * Retorna la etiqueta dinámica del identificador fiscal (RNC, Cédula, RFC, etc.)
     * Basado en la configuración general y el tipo de cliente.
     */

    public function getTaxLabelAttribute(): string
    {
        // Usamos una variable estática para no consultar la DB 50 veces en una lista
        static $taxTypes = [];

        $config = ConfiguracionGeneral::actual();
        if (!$config) return 'ID Fiscal';

        $entityType = ($this->type === 'individual') ? 'person' : 'company';
        
        // Creamos una llave única por país y tipo para el cache en memoria
        $cacheKey = "{$config->country_id}_{$entityType}";

        if (!isset($taxTypes[$cacheKey])) {
            $taxTypes[$cacheKey] = TaxIdentifierType::where('country_id', $config->country_id)
                ->where(function($query) use ($entityType) {
                    $query->where('entity_type', $entityType)
                        ->orWhere('entity_type', 'both');
                })
                ->first();
        }

        return $taxTypes[$cacheKey] ? $taxTypes[$cacheKey]->code : 'ID Fiscal';
    }

    /* ===========================
     |    COMPORTAMIENTO
     =========================== */

    public function toggleActivo(): void
    {
        $this->active = ! $this->active;
        $this->save();
    }
}