<?php

namespace App\Models\Geography;

use App\Models\Geography\Municipio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provincia extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = ['nombre', 'estado'];

    /**
     * Relación con Municipios
     */
    public function municipios()
    {
        return $this->hasMany(Municipio::class)->orderBy('nombre');
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
