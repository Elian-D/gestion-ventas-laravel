<?php

namespace App\Models\Configuration;

use App\Models\Accounting\AccountingAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoPago extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre', 'estado', 'accounting_account_id']; // Actualizado

    protected $casts = ['estado' => 'boolean'];

    // Scopes para filtrar por estado
    public function scopeActivo($query)
    {
        return $query->where('estado', true);
    }

    public function scopeInactivo($query)
    {
        return $query->where('estado', false);
    }

    public function toggleEstado(): void
    {
        $this->estado = ! $this->estado;
        $this->save();
    }

        public function account()
    {
        return $this->belongsTo(AccountingAccount::class, 'accounting_account_id');
    }
}
