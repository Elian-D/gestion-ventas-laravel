<?php

namespace App\Http\Requests\Sales\Pos\PosTerminals;

use Illuminate\Foundation\Http\FormRequest;

class StorePosTerminalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create pos terminals');
    }

    public function rules(): array
    {
        return [
            'name'                => 'required|string|max:100|unique:pos_terminals,name',
            'warehouse_id'        => 'required|exists:warehouses,id',
            'cash_account_id'     => 'required|exists:accounting_accounts,id',
            'default_ncf_type_id' => 'nullable|exists:ncf_types,id',
            'default_client_id'   => 'nullable|exists:clients,id', // Null = Heredar Global
            'is_mobile'           => 'boolean',
            'printer_format'      => 'nullable|in:80mm,58mm', // Null = Heredar Global
            'is_active'           => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'El nombre de la caja/terminal es obligatorio.',
            'name.unique'              => 'Ya existe una terminal con este nombre.',
            'warehouse_id.required'    => 'Debe asignar un almacén para el descuento de stock.',
            'cash_account_id.required' => 'Debe vincular una cuenta contable de caja.',
            'printer_format.in'        => 'El formato seleccionado no es válido (80mm o 58mm).',
        ];
    }
}