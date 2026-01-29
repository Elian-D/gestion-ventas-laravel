<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaSemana extends Model
{
    use HasFactory;

    //Constantes 
    public const LUNES = 'mon';
    public const MARTES = 'tue';
    public const MIERCOLES = 'wed';
    public const JUEVES = 'thu';
    public const VIERNES = 'fri';
    public const SABADO = 'sat';
    public const DOMINGO = 'sun';

    protected $table = 'dias_semana';

    protected $fillable = [
        'nombre',
        'codigo',
        'orden',
        'estado',
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
