<?php

namespace App\Http\Requests\Sales\Pos\PosTerminals;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePosTerminalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit pos terminals');
    }

    public function rules(): array
    {
        $terminal = $this->route('pos_terminal');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pos_terminals')->ignore($terminal->id)
            ],
            'warehouse_id'        => 'required|exists:warehouses,id',
            'default_ncf_type_id' => 'nullable|exists:ncf_types,id',
            'default_client_id'   => 'nullable|exists:clients,id',
            'is_mobile'           => 'boolean',
            'printer_format'      => 'nullable|in:80mm,58mm', 
            'is_active'           => 'boolean',
            'requires_pin' => 'boolean',
            'access_pin'   => [
                'nullable',
                'numeric',
                'digits:4',
                // Obligatorio si se activa 'requires_pin' y la terminal NO tiene un PIN previo
                Rule::requiredIf(function () use ($terminal) {
                    return $this->requires_pin && is_null($terminal->access_pin);
                }),
            ],
        ];
    }
}