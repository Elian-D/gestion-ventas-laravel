<?php

namespace App\Services\Sales\SalesServices;

use App\Models\Sales\Sale;
use App\Models\Clients\Client;
use App\Models\Inventory\{Warehouse, InventoryStock};
use App\Models\Accounting\{AccountingAccount, DocumentType};
use Illuminate\Support\Facades\DB;

class SaleCatalogService
{
    /**
     * Datos para los filtros de la tabla principal de Ventas.
     */
    public function getForFilters(): array
    {
        return [
            'clients' => Client::whereHas('sales')
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),

            'warehouses' => Warehouse::select('id', 'name')
                ->orderBy('name')
                ->get(),

            'payment_types' => Sale::getPaymentTypes(),
            'statuses'      => Sale::getStatuses(),
        ];
    }

    /**
     * Datos para el formulario de Venta (Ventanilla o POS).
     */
    public function getForForm(): array
    {
        return [
            // 1. Clientes: Priorizando Consumidor Final
            'clients' => Client::with('estadoCliente.categoria')
                ->whereHas('estadoCliente.categoria', function ($query) {
                    // Solo enviamos Clientes Operativos o con Restricción Financiera (Morosos)
                    $query->whereIn('code', ['OPERATIVO', 'FINANCIERO_RESTRICTO']);
                })
                ->select('id', 'name', 'credit_limit', 'balance', 'estado_cliente_id')
                ->orderByRaw("CASE WHEN name = 'Consumidor Final' THEN 0 ELSE 1 END")
                ->orderBy('name')
                ->get()
                ->map(function ($client) {
                    return [
                        'id'           => $client->id,
                        'name'         => $client->name,
                        'credit_limit' => $client->credit_limit,
                        'balance'      => $client->balance,
                        'available'    => $client->credit_limit - $client->balance,
                        // Moroso = FINANCIERO_RESTRICTO
                        'is_moroso'    => ($client->estadoCliente->categoria->code ?? '') === 'FINANCIERO_RESTRICTO',
                        'status_name'  => $client->estadoCliente->nombre ?? 'N/A',
                    ];
                }),

            // 2. Almacenes: Para saber de dónde sale el hielo
            'warehouses' => Warehouse::select('id', 'name', 'type')
                ->get(),

            // 3. Productos con Stock: Relacionados con sus almacenes
            // Traemos los productos que tienen existencia en al menos un lugar
            'products' => InventoryStock::with(['product' => function($query) {
                $query->select('id', 'name', 'price');
            }])
            ->where('quantity', '>', 0)
            ->get()
            ->filter(fn($stock) => $stock->product !== null) // Evita errores si un stock no tiene producto
            ->map(function ($stock) {
                return [
                    'id'           => $stock->product_id,
                    'name'         => $stock->product->name,
                    'price'        => $stock->product->price,
                    'warehouse_id' => $stock->warehouse_id,
                    'stock'        => $stock->quantity,
                ];
            })->values()->toArray(),

            // 4. Configuración de Documento (Para previsualizar el siguiente folio)
            'document_config' => DocumentType::where('code', 'FAC')
                ->select('id', 'prefix', 'current_number')
                ->first(),

            'payment_types' => Sale::getPaymentTypes(),
            
            // Cuenta contable para ventas al contado (Default: Caja)
            'default_cash_account' => AccountingAccount::where('code', '1.1.01')
                ->select('id', 'name', 'code')
                ->first(),
        ];
    }
}