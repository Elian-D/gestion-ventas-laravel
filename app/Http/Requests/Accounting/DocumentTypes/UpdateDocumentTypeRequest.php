<?php

namespace App\Http\Requests\Accounting\DocumentTypes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocumentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit document types');
    }

    public function rules(): array
    {
        $type = $this->route('document_type');

        return [
            'name'   => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('document_types')->ignore($type->id)
            ],
            'prefix'                    => 'nullable|string|max:5',
            'default_debit_account_id'  => 'nullable|exists:accounting_accounts,id',
            'default_credit_account_id' => 'nullable|exists:accounting_accounts,id',
            'is_active'                 => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'El nombre ya está siendo utilizado por otro documento.',
            'code.unique' => 'Este código ya pertenece a otro registro.',
            'current_number.required' => 'Debe definir el número actual del correlativo.',
        ];
    }
}