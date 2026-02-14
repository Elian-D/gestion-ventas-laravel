<?php

namespace App\Http\Requests\Sales\Pos\CashMovements;

use App\Models\Sales\Pos\PosCashMovement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCashMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('pos cash movements create');
    }

    public function rules(): array
    {
        return [
            'pos_session_id' => ['required', 'exists:pos_sessions,id'],
            'type'           => ['required', Rule::in([PosCashMovement::TYPE_IN, PosCashMovement::TYPE_OUT])],
            'amount'         => ['required', 'numeric', 'min:0.01'],
            'reason'         => ['required', 'string', 'min:5', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min'      => 'El monto debe ser mayor a cero.',
            'reason.required' => 'El motivo es obligatorio para el arqueo de caja.',
        ];
    }
}