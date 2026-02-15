<?php

namespace App\Http\Requests\Sales\Pos\PosConfig;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePosConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('pos config update');
    }

    /**
     * Prepara los datos antes de validar
     */
    protected function prepareForValidation()
    {
        // Convertir checkboxes ausentes en false
        $this->merge([
            'allow_item_discount' => $this->has('allow_item_discount'),
            'allow_global_discount' => $this->has('allow_global_discount'),
            'allow_quick_customer_creation' => $this->has('allow_quick_customer_creation'),
            'allow_quote_without_save' => $this->has('allow_quote_without_save'),
            'auto_print_receipt' => $this->has('auto_print_receipt'),
        ]);
    }

    public function rules(): array
    {
        return [
            'allow_item_discount'           => 'boolean',
            'allow_global_discount'         => 'boolean',
            'max_discount_percentage'       => 'required|numeric|min:0|max:100',
            'allow_quick_customer_creation' => 'boolean',
            'default_walkin_customer_id'    => 'required|exists:clients,id',
            'allow_quote_without_save'      => 'boolean',
            'auto_print_receipt'            => 'boolean',
            'receipt_size'                  => 'required|in:58mm,80mm',
        ];
    }

    public function attributes(): array
    {
        return [
            'default_walkin_customer_id' => 'Cliente por defecto',
            'max_discount_percentage'    => 'Porcentaje máximo de descuento',
            'receipt_size'               => 'Tamaño del recibo',
        ];
    }

    public function messages(): array
    {
        return [
            'max_discount_percentage.required' => 'El porcentaje máximo de descuento es obligatorio.',
            'max_discount_percentage.min' => 'El porcentaje debe ser al menos 0.',
            'max_discount_percentage.max' => 'El porcentaje no puede superar 100.',
            'default_walkin_customer_id.required' => 'Debes seleccionar un cliente por defecto.',
            'default_walkin_customer_id.exists' => 'El cliente seleccionado no existe.',
            'receipt_size.required' => 'Debes seleccionar un tamaño de recibo.',
        ];
    }
}