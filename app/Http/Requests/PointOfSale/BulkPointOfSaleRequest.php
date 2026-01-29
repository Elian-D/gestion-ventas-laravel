<?php

namespace App\Http\Requests\PointOfSale;

use Illuminate\Foundation\Http\FormRequest;

class BulkPointOfSaleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'ids'    => 'required|array',
            'ids.*'  => 'exists:point_of_sales,id',
            'action' => 'required|in:delete,change_active,change_geo_state,change_client',
            'value'  => 'nullable'
        ];
    }
}