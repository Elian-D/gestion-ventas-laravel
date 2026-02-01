<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit products');
    }

    public function rules(): array
    {
        // Obtenemos el ID del producto desde la ruta para la validación del SKU único
        $productId = $this->route('product')->id;

        return [
            'category_id'  => 'required|exists:categories,id',
            'unit_id'      => 'required|exists:units,id',
            'name'         => 'required|string|max:150',
            'sku'          => "nullable|string|max:50|unique:products,sku,{$productId}",
            'description'  => 'nullable|string|max:1000',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            
            'price'        => 'required|numeric|min:0',
            'cost'         => 'required|numeric|min:0',
            
            'is_active'    => 'boolean',
            'is_stockable' => 'boolean',
        ];
    }
}