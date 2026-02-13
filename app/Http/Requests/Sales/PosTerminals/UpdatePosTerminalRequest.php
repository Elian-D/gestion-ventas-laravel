<?php

namespace App\Http\Requests\Sales\PosTerminals;

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
            'cash_account_id'     => 'required|exists:accounting_accounts,id',
            'default_ncf_type_id' => 'nullable|exists:ncf_types,id',
            'default_client_id'   => 'nullable|exists:clients,id',
            'is_mobile'           => 'boolean',
            'printer_format'      => 'required|in:80mm,58mm',
            'is_active'           => 'boolean',
        ];
    }
}