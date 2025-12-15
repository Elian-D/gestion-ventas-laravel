<?php

namespace App\Models\Geography;

use App\Models\Geography\Sector;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atributos que se pueden asignar masivamente
     */
    protected $fillable = ['provincia_id', 'nombre', 'estado'];

    /**
     * Relación con la Provincia a la que pertenece
     */
    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    /**
     * Relación con los Sectores que pertenecen al municipio
     */
    public function sectores()
    {
        return $this->hasMany(Sector::class)->orderBy('nombre');
    }

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
