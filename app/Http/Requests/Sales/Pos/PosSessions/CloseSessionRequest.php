<?php

namespace App\Http\Requests\Sales\Pos\PosSessions;

use Illuminate\Foundation\Http\FormRequest;

class CloseSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('pos sessions manage');
    }

    public function rules(): array
    {
        return [
            'closing_balance' => ['required', 'numeric', 'min:0'],
            'notes'           => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'closing_balance.required' => 'Debe ingresar el monto total contado en caja.',
            'closing_balance.min'      => 'El monto de cierre no puede ser negativo.',
        ];
    }
}