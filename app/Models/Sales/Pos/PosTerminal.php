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
use Illuminate\Support\Facades\Hash;

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
        'is_active',
        'access_pin',   // Añadido
        'requires_pin'  // Añadido
    ];

    protected $casts = [
        'is_mobile'    => 'boolean',
        'is_active'    => 'boolean',
        'requires_pin' => 'boolean', // Añadido
    ];

    protected $hidden = [
        'access_pin', // Ocultar de arrays/JSON para seguridad
    ];

    // ===== LÓGICA DE SEGURIDAD (PIN) =====

    /**
     * Mutator para hashear el PIN automáticamente al asignarlo.
     */
    protected function setAccessPinAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['access_pin'] = Hash::make($value);
        }
    }

    /**
     * Verifica si el PIN proporcionado es correcto.
     */
    public function verifyPin(string $pin): bool
    {
        if (!$this->requires_pin) return true;
        
        return Hash::check($pin, $this->access_pin);
    }

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