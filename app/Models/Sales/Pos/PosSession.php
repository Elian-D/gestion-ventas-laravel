<?php

namespace App\Models\Sales\Pos;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'terminal_id',
        'user_id',
        'status',
        'opened_at',
        'closed_at',
        'opening_balance',
        'closing_balance',
        'notes',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
    ];

    // --- Constantes de Estado ---
    const STATUS_OPEN   = 'open';
    const STATUS_CLOSED = 'closed';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_OPEN   => 'Abierta',
            self::STATUS_CLOSED => 'Cerrada',
        ];
    }

    public static function getStatusStyles(): array
    {
        return [
            self::STATUS_OPEN   => 'bg-emerald-100 text-emerald-700 border-emerald-200 ring-emerald-500/10',
            self::STATUS_CLOSED => 'bg-gray-100 text-gray-700 border-gray-200 ring-gray-500/10',
        ];
    }

    public static function getStatusIcons(): array
    {
        return [
            self::STATUS_OPEN   => 'heroicon-s-lock-open',
            self::STATUS_CLOSED => 'heroicon-s-lock-closed',
        ];
    }

    // --- Relaciones ---

    public function terminal(): BelongsTo
    {
        return $this->belongsTo(PosTerminal::class, 'terminal_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // --- Scopes ---

    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    // --- Helpers de Instancia ---

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }
}