<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $table = 'warehouses';

    protected $fillable = [
        'code',
        'name',
        'type',
        'address',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /* ===========================
     |  CONSTANTES DE TIPOS
     =========================== */
    
    const TYPE_STATIC = 'static';
    const TYPE_MOBILE = 'mobile';
    const TYPE_POS    = 'pos';

    public static function getTypes(): array
    {
        return [
            self::TYPE_STATIC => 'Estático (Bodega/Fábrica)',
            self::TYPE_MOBILE => 'Móvil (Camión/Ruta)',
            self::TYPE_POS    => 'Punto de Venta',
        ];
    }

    /* ===========================
     |  RELACIONES
     =========================== */

    /**
     * Relación: Un almacén tiene muchos balances de stock
     */
/*     public function stocks()
    {
        return $this->hasMany(InventoryStock::class);
    }
 */
    /* ===========================
     |  SCOPES
     =========================== */

    public function scopeActivos($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /* ===========================
     |  COMPORTAMIENTO Y LÓGICA
     =========================== */

    /**
     * Generar código automático basado en nombre e ID.
     */
    public function generateCode(): void
    {
        if (!$this->id) {
            $this->save(); 
        }

        // Tomamos las primeras 3 letras del slug del nombre (ej: "Bodega Central" -> "BOD")
        $prefix = strtoupper(substr(Str::slug($this->name), 0, 3));
        
        // Si el nombre es muy corto, Str::slug podría dar menos de 3 letras, 
        // pero substr lo maneja bien.
        $this->code = $prefix . '-' . $this->id;
        
        $this->save();
    }


    /**
     * Alternar el estado activo/inactivo
     */
    public function toggleActivo(): void
    {
        $this->is_active = !$this->is_active;
        $this->save();
    }
    
    /**
     * Formatear el nombre del tipo para la UI
     */
    protected function typeLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => self::getTypes()[$this->type] ?? $this->type,
        );
    }
}