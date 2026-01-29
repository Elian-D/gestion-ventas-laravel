<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientStateCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /* ===========================
     |  RELACIONES
     =========================== */

    public function estadosClientes()
    {
        return $this->hasMany(EstadosCliente::class, 'client_state_category_id');
    }
}
