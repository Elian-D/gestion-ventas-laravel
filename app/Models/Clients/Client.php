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
        'address',
        'tax_identifier_type_id',
        'tax_id',
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

    /**
     * Obtiene el tipo de identificador fiscal (RNC, Cédula, RFC, etc.)
     */

    public function taxIdentifierType(): BelongsTo
    {
        return $this->belongsTo(TaxIdentifierType::class, 'tax_identifier_type_id');
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
     * Retorna la sigla o nombre del identificador fiscal real del cliente.
     * Ejemplo: "RNC", "Cédula", "DNI", etc.
     */
    public function getTaxLabelAttribute(): string
    {
        // 1. Intentamos obtener el código desde la relación cargada
        // Esto es mucho más preciso que adivinar por el tipo de cliente.
        if ($this->taxIdentifierType) {
            return $this->taxIdentifierType->code ?? $this->taxIdentifierType->name;
        }

        // 2. Fallback: Si por alguna razón no tiene tipo asignado, 
        // usamos la lógica de configuración general como último recurso.
        $config = general_config();
        if (!$config) return 'ID Fiscal';

        $entityType = $this->type === 'individual' ? 'person' : 'company';
        
        // Podríamos cachear esto, pero lo ideal es que el cliente siempre tenga su tipo_id
        $default = TaxIdentifierType::where('country_id', $config->country_id)
                    ->whereIn('entity_type', [$entityType, 'both'])
                    ->first();

        return $default?->code ?? 'ID Fiscal';
    }

    /* ===========================
    |      SCOPES
    =========================== */

    public function scopeWithIndexRelations($query)
{
    return $query->with([
        'estadoCliente:id,nombre,clase_fondo,clase_texto',
        'state:id,name',
        'taxIdentifierType:id,name,code',
    ]);
}
}