<?php

namespace App\Http\Requests\Sales;

use App\Models\Sales\Sale;
use App\Models\Inventory\InventoryStock;
use App\Models\Clients\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create sales');
    }

    public function rules(): array
    {
        return [
            'client_id'    => ['required', 'exists:clients,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'sale_date'    => ['required', 'date', 'after_or_equal:today', 'before_or_equal:today'],
            'payment_type' => ['required', Rule::in([Sale::PAYMENT_CASH, Sale::PAYMENT_CREDIT])],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'apply_tax'    => ['nullable', 'boolean'], // Recibimos el estado del switch
            'notes'        => ['nullable', 'string', 'max:255'],

            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'numeric', 'min:0.01'],
            'items.*.price'      => ['required', 'numeric', 'min:0'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) return;

            $subtotalCalculated = 0;
            
            foreach ($this->items as $index => $item) {
                $subtotalCalculated += ($item['quantity'] * $item['price']);

                // Validar Stock
                $stock = InventoryStock::where('warehouse_id', $this->warehouse_id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                if (!$stock || $stock->quantity < $item['quantity']) {
                    $available = $stock ? $stock->quantity : 0;
                    $validator->errors()->add("items.{$index}.quantity", "Stock insuficiente. Disponible: {$available}.");
                }
            }

            // --- LÓGICA DE IMPUESTOS ---
            $totalFinal = $subtotalCalculated;
            if ($this->boolean('apply_tax')) {
                $taxRate = general_config()->impuesto->valor ?? 0;
                $taxAmount = $subtotalCalculated * ($taxRate / 100);
                $totalFinal = $subtotalCalculated + $taxAmount;
            }

            if (abs($totalFinal - $this->total_amount) > 0.01) {
                $validator->errors()->add('total_amount', 'El total no coincide con la suma de los productos + impuestos.');
            }

            // --- LÓGICA DE CRÉDITO Y ESTADOS ---
            if ($this->payment_type === Sale::PAYMENT_CREDIT) {
                $client = Client::with('estadoCliente.categoria')->find($this->client_id);
                
                if (!$client) return;

                // 1. Regla Consumidor Final
                if ($client->id == 1 || $client->name === 'Consumidor Final') {
                    $validator->errors()->add('payment_type', 'El Consumidor Final no puede procesar ventas a crédito.');
                    return;
                }

                // 2. Validación por Categoría de Estado (Usando tu Seeder)
                $categoryCode = $client->estadoCliente->category->code ?? null;

                if (in_array($categoryCode, ['BLOQUEO_TOTAL', 'FINANCIERO_RESTRICTO'])) {
                    $validator->errors()->add('client_id', "Crédito denegado: El cliente tiene un estado de {$client->estadoCliente->nombre}.");
                }

                // 3. Validación de Límite de Crédito
                $nuevoSaldoProyectado = $client->balance + $this->total_amount;
                if ($nuevoSaldoProyectado > $client->credit_limit) {
                    $disponible = number_format($client->credit_limit - $client->balance, 2);
                    $validator->errors()->add('total_amount', "Límite de crédito superado. Disponible: \${$disponible}.");
                }
            }
        });
    }
}