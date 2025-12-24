<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadosCliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'estados_clientes';

    protected $fillable = [
        'nombre',
        'activo',
        'permite_operar',
        'permite_facturar',
        'clase_fondo',
        'clase_texto',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'permite_operar' => 'boolean',
        'permite_facturar' => 'boolean',
    ];

    /* ===========================
     |  SCOPES DEL CATÁLOGO
     =========================== */

    // Estados utilizables
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Estados deshabilitados
    public function scopeInactivos($query)
    {
        return $query->where('activo', false);
    }

    // Scope flexible por estado (activo/inactivo)
    public function scopeFiltrarPorEstado($query, ?string $estado)
    {
        return match ($estado) {
            'activo' => $query->activos(),
            'inactivo' => $query->inactivos(),
            default => $query,
        };
    }

    /**
     * Scope flexible por nombre del estado
     * Ej: EstadosCliente::porNombre('Moroso')->first()
     */
    public function scopePorNombre($query, string $nombre)
    {
        return $query->whereRaw('LOWER(nombre) = ?', [strtolower($nombre)]);
    }

    /* ===========================
     |  COMPORTAMIENTO
     =========================== */

    public function toggleActivo(): void
    {
        $this->activo = ! $this->activo;
        $this->save();
    }

    public function puedeOperar(): bool
    {
        return $this->activo && $this->permite_operar;
    }

    public function puedeFacturar(): bool
    {
        return $this->activo && $this->permite_facturar;
    }
}
