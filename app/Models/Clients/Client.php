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
        static $taxTypes = [];

        $config = general_config();
        if (! $config) return 'ID Fiscal';

        $entityType = $this->type === 'individual' ? 'person' : 'company';
        $cacheKey = "{$config->country_id}_{$entityType}";

        if (! isset($taxTypes[$cacheKey])) {
            $taxTypes[$cacheKey] = TaxIdentifierType::query()
                ->select('code')
                ->where('country_id', $config->country_id)
                ->whereIn('entity_type', [$entityType, 'both'])
                ->first();
        }

        return $taxTypes[$cacheKey]?->code ?? 'ID Fiscal';
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