<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadosCliente extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = ['nombre', 'estado', 'clase_fondo', 'clase_texto'];

    protected $casts = [
    'estado' => 'boolean',
];


    // Scopes para filtrar por estado
    public function scopeActivo($query)
    {
        return $query->where('estado', true);
    }

    public function scopeInactivo($query)
    {
        return $query->where('estado', false);
    }

    // Alternar estado
    public function toggleEstado(): void
    {
        $this->estado = ! $this->estado;
        $this->save();
    }
}
