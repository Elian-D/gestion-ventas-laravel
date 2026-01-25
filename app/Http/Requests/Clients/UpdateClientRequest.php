<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Usamos el permiso del Seeder: 'clients edit'
        return $this->user()->can('clients edit');
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
            'tax_identifier_type_id' => 'required|exists:tax_identifier_types,id',
            'tax_id'                 => 'required|string|max:50',
        ];
    }
}