<?php

namespace app\Models\Sales\Pos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Inventory\Warehouse;
use App\Models\Accounting\AccountingAccount;
use App\Models\Sales\Ncf\NcfType;
use App\Models\Clients\Client;

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

    // Una terminal puede tener muchas sesiones (aperturas/cierres)
    // public function sessions()
    // {
    //     return $this->hasMany(PosSession::class); // Se creará en la siguiente rama
    // }
}