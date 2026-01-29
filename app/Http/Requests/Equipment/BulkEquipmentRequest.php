<?php

namespace App\Http\Requests\Equipment;

use Illuminate\Foundation\Http\FormRequest;

class BulkEquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('equipment edit');
    }

    public function rules(): array
    {
        return [
            'ids'    => 'required|array',
            'ids.*'  => 'exists:equipments,id',
            'action' => 'required|in:delete,change_active,change_type,change_pos',
            'value'  => 'nullable',
        ];
    }
}
