<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'code', 
        'prefix', 
        'current_number', 
        'default_debit_account_id', 
        'default_credit_account_id', 
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    protected static function booted()
    {
        static::saving(function ($type) {
            // Generar código único (ej: FAC) si está vacío
            if (empty($type->code)) {
                $type->code = self::makeCode($type->name);
            }
            // El prefijo suele ser igual al código en documentos contables
            if (empty($type->prefix)) {
                $type->prefix = $type->code;
            }
        });
    }

    public static function makeCode(string $name): string
    {
        // Toma las primeras 3 letras del nombre, sin caracteres especiales
        return strtoupper(
            substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3)
        );
    }

    /* ===========================
     |  RELACIONES
     =========================== */

    public function defaultDebitAccount(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'default_debit_account_id');
    }

    public function defaultCreditAccount(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'default_credit_account_id');
    }

    /* ===========================
     |  LÓGICA DE NEGOCIO
     =========================== */

    /**
     * Genera el siguiente número formateado (Ej: FAC-000001)
     * No actualiza la base de datos, solo retorna el string.
     */
    public function getNextNumberFormatted(): string
    {
        $next = $this->current_number + 1;
        return sprintf(
            '%s-%06d',
            strtoupper($this->prefix),
            $next
        );
    }

    public function scopeWithIndexRelations($query)
    {
        return $query->with([
            'defaultDebitAccount:id,code,name',
            'defaultCreditAccount:id,code,name',
        ]);
    }
}