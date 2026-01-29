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
        'client_state_category_id',
        'activo',
        'clase_fondo',
        'clase_texto',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /* ===========================
     |  RELACIONES
     =========================== */

    public function categoria()
    {
        return $this->belongsTo(ClientStateCategory::class, 'client_state_category_id');
    }

    /* ===========================
     |  SCOPES DE CATÁLOGO
     =========================== */

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeInactivos($query)
    {
        return $query->where('activo', false);
    }

    public function scopePorNombre($query, string $nombre)
    {
        return $query->whereRaw('LOWER(nombre) = ?', [strtolower($nombre)]);
    }
}
