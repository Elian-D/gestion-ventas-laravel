<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class BulkProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit products'); 
    }

    public function rules(): array
    {
        return [
            'ids'    => 'required|array',
            'ids.*'  => 'exists:products,id',
            'action' => 'required|in:change_active,change_stockable,change_category,change_unit',
            'value'  => 'nullable'
        ];
    }
}