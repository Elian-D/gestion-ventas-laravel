<?php

namespace App\Models\Configuration;

use App\Models\Accounting\AccountingAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoPago extends Model
{
    use HasFactory, SoftDeletes;

    // Constantes para lógica de negocio (Slugs)
    const EFECTIVO = 'efectivo';
    const TRANSFERENCIA = 'transferencia';
    const TARJETA = 'tarjeta';
    const CHEQUE = 'cheque';
    const CREDITO = 'credito'; // Para ventas que generan CxC

    protected $fillable = ['nombre', 'slug', 'estado', 'accounting_account_id'];

        protected $casts = ['estado' => 'boolean'];

    // Lista de métodos que el sistema necesita para funcionar
    public static function getSystemMethods(): array
    {
        return [self::EFECTIVO, self::TRANSFERENCIA, self::TARJETA, self::CHEQUE, self::CREDITO];
    }

    /**
     * Helper para verificar si es efectivo sin importar el nombre o ID
     */
    public function isCash(): bool {
        return $this->slug === self::EFECTIVO;
    }

    public function isSystemProtected(): bool
    {
        // Lista de slugs que no se pueden borrar ni editar nombre
        $protected = ['efectivo', 'transferencia-bancaria', 'cheque', 'tarjeta-de-creditodebito', 'credito'];
        return in_array($this->slug, $protected);
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
