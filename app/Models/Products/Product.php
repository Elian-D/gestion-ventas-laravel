<?php

// app/Models/Products/Product.php
namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'unit_id', 'name', 'slug', 'sku', 'description', 
        'image_path', 'price', 'cost', 'is_active', 'is_stockable'
    ];

    /* ===========================
     |  ASESORES    
     =========================== */

    public function getFormattedPriceAttribute(): string
    {
        $config = general_config();
        $symbol = $config->currency_symbol ?? '$';
        
        return $symbol . ' ' . number_format($this->price, 2);
    }

    /* ===========================
     |  RELACIONES
     =========================== */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    /* ===========================
     |  SCOPES
     =========================== */
    /**
     * Tarea ERP Pattern: Centralizar Eager Loading
     */
    public function scopeWithIndexRelations(Builder $query): void
    {
        $query->with([
            'category:id,name',
            'unit:id,name,abbreviation'
        ]); // Solo traemos lo necesario
    }

    public function scopeActivo(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactivo(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    public function scopeStockable(Builder $query): Builder
    {
        return $query->where('is_stockable', true);
    }
}