<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create products');
    }

    public function rules(): array
    {
        return [
            'category_id'  => 'required|exists:categories,id',
            'unit_id'      => 'required|exists:units,id',
            'name'         => 'required|string|max:150',
            'sku'          => 'nullable|string|max:50|unique:products,sku',
            'description'  => 'nullable|string|max:1000',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            
            // Precios: min 0 para no permitir valores negativos
            'price'        => 'required|numeric|min:0',
            'cost'         => 'required|numeric|min:0',
            
            // Flags
            'is_active'    => 'boolean',
            'is_stockable' => 'boolean',
        ];
    }
}