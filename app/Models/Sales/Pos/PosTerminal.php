<?php

namespace App\Models\Sales\Pos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Inventory\Warehouse;
use App\Models\Accounting\AccountingAccount;
use App\Models\Sales\Ncf\NcfType;
use App\Models\Clients\Client;
use App\Models\Sales\Sale;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PosTerminal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'warehouse_id',
        'cash_account_id',
        'default_ncf_type_id',
        'default_client_id',
        'is_mobile',
        'printer_format',
        'is_active'
    ];

    protected $casts = [
        'is_mobile' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ===== RELACIONES =====

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function cashAccount()
    {
        return $this->belongsTo(AccountingAccount::class, 'cash_account_id');
    }

    public function defaultNcfType()
    {
        return $this->belongsTo(NcfType::class, 'default_ncf_type_id');
    }

    public function defaultClient()
    {
        return $this->belongsTo(Client::class, 'default_client_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'pos_terminal_id');
    }

    // Una terminal puede tener muchas sesiones (aperturas/cierres)

    public function sessions(): HasMany
    {
        // El segundo parámetro es la columna real en la tabla pos_sessions
        return $this->hasMany(PosSession::class, 'terminal_id');
    }
    /**
     * Obtiene una configuración específica resolviendo la jerarquía:
     * Terminal (si existe) -> Global (fallback)
     */
    public function getSetting(string $key)
    {
        return match($key) {
            'printer_format' => $this->printer_format ?? pos_config('receipt_size'),
            'default_client' => $this->default_client_id ?? pos_config('default_walkin_customer_id'),
            'auto_print'     => pos_config('auto_print_receipt'), // No sobreescribible por ahora
            default          => pos_config($key)
        };
    }
}