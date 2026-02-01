<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class JournalEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'entry_date',
        'reference',
        'description',
        'status',
        'created_by'
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    // Constantes de Estado
    const STATUS_DRAFT     = 'draft';
    const STATUS_POSTED    = 'posted';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT     => 'Borrador',
            self::STATUS_POSTED    => 'Asentado',
            self::STATUS_CANCELLED => 'Anulado',
        ];
    }

    public static function getStatusStyles(): array
    {
        return [
            self::STATUS_DRAFT     => 'bg-gray-100 text-gray-700 border-gray-200 ring-gray-500/10',
            self::STATUS_POSTED    => 'bg-emerald-100 text-emerald-700 border-emerald-200 ring-emerald-500/10',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-700 border-red-200 ring-red-500/10',
        ];
    }

    // Relaciones
    public function items(): HasMany
    {
        return $this->hasMany(JournalItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helpers para cálculo rápido
    public function getTotalDebitAttribute()
    {
        return $this->items->sum('debit');
    }

    public function getTotalCreditAttribute()
    {
        return $this->items->sum('credit');
    }

    public function isBalanced(): bool
    {
        return abs($this->total_debit - $this->total_credit) < 0.001;
    }
}