<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Clients\Client;
use App\Models\Inventory\Warehouse;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'document_type_id', 
        'number',
        'client_id',
        'warehouse_id',
        'user_id',
        'sale_date',
        'total_amount',
        'payment_type',
        'cash_received', // Nuevo
        'cash_change',   // Nuevo
        'status',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
    ];

    // Constantes de Estado
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELED  = 'canceled';

    // Constantes de Tipo de Pago
    const PAYMENT_CASH   = 'cash';
    const PAYMENT_CREDIT = 'credit';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_COMPLETED => 'Completada',
            self::STATUS_CANCELED  => 'Anulada',
        ];
    }

    public static function getPaymentTypes(): array
    {
        return [
            self::PAYMENT_CASH   => 'Contado',
            self::PAYMENT_CREDIT => 'Crédito',
        ];
    }

    // Estilos para los Estados (Status)
    public static function getStatusStyles(): array
    {
        return [
            self::STATUS_COMPLETED => 'bg-emerald-100 text-emerald-700 border-emerald-200 ring-emerald-500/10',
            self::STATUS_CANCELED  => 'bg-red-100 text-red-700 border-red-200 ring-red-500/10',
        ];
    }

    // NUEVO: Estilos para Tipos de Pago (Badges)
    public static function getPaymentTypeStyles(): array
    {
        return [
            self::PAYMENT_CASH   => 'bg-blue-100 text-blue-700 border-blue-200 ring-blue-500/10',
            self::PAYMENT_CREDIT => 'bg-amber-100 text-amber-700 border-amber-200 ring-amber-500/10',
        ];
    }

    // NUEVO: Iconos para Tipos de Pago (Heroicons)
    public static function getPaymentTypeIcons(): array
    {
        return [
            self::PAYMENT_CASH   => 'heroicon-s-banknotes',
            self::PAYMENT_CREDIT => 'heroicon-s-credit-card',
        ];
    }

    /**
     * Centraliza las relaciones necesarias para el Index y Exportaciones
     */
    public function scopeWithIndexRelations($query)
    {
        return $query->with([
            'client:id,name,tax_id', 
            'user:id,name', 
            'warehouse:id,name',
            'items', // Cargamos todos los campos de los items (precio, cantidad)
            'items.product:id,name,sku' // Cargamos el producto de cada item
        ]);
    }

    // Relaciones ...
    public function items(): HasMany { return $this->hasMany(SaleItem::class); }
    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function warehouse(): BelongsTo{return $this->belongsTo(Warehouse::class);}
    public function invoice(): HasOne{return $this->hasOne(Invoice::class);}
}