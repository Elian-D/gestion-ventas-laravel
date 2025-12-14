<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $fillable = ['nombre', 'estado'];
    
    public $timestamps = true;
    
    public function toggleEstado(): void
    {
        $this->estado = ! $this->estado;
        $this->save();
    }
}
