<?php

namespace App\Http\Requests\Accounting\Payment;

use App\Models\Accounting\Receivable;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create payments');
    }

    public function rules(): array
    {
        return [
            'receivable_id'  => ['required', 'exists:receivables,id'],
            'tipo_pago_id'   => ['required', 'exists:tipo_pagos,id'],
            'amount'         => [
                'required', 
                'numeric', 
                'min:0.01',
                function ($attribute, $value, $fail) {
                    $receivable = Receivable::find($this->receivable_id);
                    if ($receivable && $value > $receivable->current_balance) {
                        $fail("El monto del pago ($value) no puede ser mayor al saldo pendiente ({$receivable->current_balance}).");
                    }
                }
            ],
            'payment_date'   => ['required', 'date', 'before_or_equal:today'],
            'reference'      => ['nullable', 'string', 'max:100'],
            'note'           => ['nullable', 'string', 'max:500'],
        ];
    }
}