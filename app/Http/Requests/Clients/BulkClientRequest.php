<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;

class BulkClientRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'exists:clients,id',
            'action' => 'required|in:delete,change_status,change_geo_state',
            'value' => 'nullable'
        ];
    }
}