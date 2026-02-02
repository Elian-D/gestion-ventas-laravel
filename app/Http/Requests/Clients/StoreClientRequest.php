<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Verificamos el permiso del Seeder que compartiste
        return $this->user()->can('clients create');
    }

    public function rules(): array
    {
        return [
            'type'                   => ['required', Rule::in(['individual', 'company'])],
            'name'                   => 'required|string|max:255',
            'commercial_name'        => 'nullable|string|max:255',
            'email'                  => 'nullable|email|max:255',
            'phone'                  => 'nullable|string|max:20',
            'estado_cliente_id'      => 'required|exists:estados_clientes,id',
            'state_id'               => 'required|exists:states,id',
            'city'                   => 'required|string|max:100',
            'address'                => 'nullable|string|max:500',
            'tax_identifier_type_id' => 'required|exists:tax_identifier_types,id',
            'tax_id'                 => ['required', 'string', 'max:50', Rule::unique('clients')->ignore($this->client)],
            
            // 👇 Nuevos campos financieros
            'credit_limit'           => 'required|numeric|min:0',
            'payment_terms'          => 'required|integer|min:0',
            'accounting_account_id'  => 'nullable|exists:accounting_accounts,id',
            'create_accounting_account' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'tax_id.unique'             => 'Este identificador fiscal ya está registrado en el sistema.',
            'credit_limit.min'          => 'El límite de crédito no puede ser un número negativo.',
            'payment_terms.integer'     => 'Los términos de pago deben ser un número de días válido.',
            'accounting_account_id.exists' => 'La cuenta contable seleccionada no es válida.',
            'estado_cliente_id.required' => 'Debe asignar un estado operativo al cliente.',
            'tax_identifier_type_id.required' => 'El tipo de documento fiscal es obligatorio.',
        ];
    }
}