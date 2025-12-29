<?php

namespace App\Models\Clients;

use App\Models\Configuration\EstadosCliente;
use App\Models\Geo\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    /* ===========================
     |    COMPORTAMIENTO
     =========================== */

    public function toggleActivo(): void
    {
        $this->active = ! $this->active;
        $this->save();
    }
}