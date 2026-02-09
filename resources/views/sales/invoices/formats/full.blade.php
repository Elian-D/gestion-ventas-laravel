@php
    $config = general_config();
    $impuestoConfig = $config->impuesto;
    $sale = $invoice->sale;
    $client = $sale->client;
    $currency = $config->currency_symbol ?? '$';
    
    // Identificador fiscal de la EMPRESA
    $taxIdentifier = \DB::table('tax_identifier_types')
                        ->where('id', $config->tax_identifier_type_id)
                        ->first();
    $taxLabel = $taxIdentifier->code ?? 'RNC';

    // Identificador fiscal del CLIENTE (RNC/Cédula)
    $clientTaxIdentifier = \DB::table('tax_identifier_types')
                        ->where('id', $client->tax_identifier_type_id)
                        ->first();
    $clientTaxLabel = $clientTaxIdentifier->code ?? 'RNC/CED';

    // Lógica de impuestos dinámica
    $taxName = $impuestoConfig->nombre ?? 'ITBIS';
    
    // CÁLCULOS DE SEGURIDAD (Igual que en el ticket)
    $subtotalCalculado = $sale->items->sum('subtotal'); 
    $taxCalculado = $sale->tax > 0 ? $sale->tax : ($sale->total_amount - $subtotalCalculado);

    // Vencimiento de factura (Crédito)
    $vencimiento = $sale->payment_type === 'credit' 
        ? $sale->created_at->addDays($client->credit_limit_days ?? 30)->format('d/m/Y') 
        : null;
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 12px; margin: 0; padding: 0; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .company-name { font-size: 20px; font-weight: bold; text-transform: uppercase; }
        .info-label { color: #666; font-size: 10px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        
        .invoice-banner { background: #f4f4f4; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .invoice-type { font-size: 16px; font-weight: bold; color: #444; }

        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th { background: #1a1a1a; color: white; padding: 8px; text-transform: uppercase; font-size: 10px; }
        .items-table td { padding: 8px; border-bottom: 1px solid #eee; }

        .totals-container { margin-top: 20px; width: 300px; float: right; }
        .total-row { padding: 5px 0; }
        .grand-total { font-size: 18px; border-top: 2px solid #333; padding-top: 10px; margin-top: 5px; }

        .footer-notes { margin-top: 50px; font-size: 10px; color: #888; border-top: 1px solid #eee; padding-top: 10px; clear: both; }
    </style>
</head>
<body>

    {{-- 1. ENCABEZADO: Empresa y Contacto --}}
    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="company-name">{{ $config->nombre_empresa }}</div>
                <div>{{ $config->direccion }}</div>
                <div>Teléfono: {{ $config->telefono }}</div>
                <div>{{ $taxLabel }}: {{ $config->tax_id }}</div>
            </td>
            <td class="text-right" style="width: 40%;">
                @if($config->logo)
                    {{-- Nota: Para DomPDF se recomienda usar public_path --}}
                    <img src="{{ public_path('storage/'.$config->logo) }}" style="max-height: 80px;">
                @endif
            </td>
        </tr>
    </table>

    {{-- 2. TIPO Y NÚMERO DE FACTURA --}}
    <div class="invoice-banner">
        <table style="width: 100%;">
            <tr>
                <td>
                    <span class="info-label">Factura No:</span><br>
                    <span class="bold">{{ $invoice->invoice_number }}</span>
                </td>
                <td class="text-right">
                    <span class="invoice-type">
                        {{ $sale->payment_type === 'credit' ? 'CRÉDITO' : 'CONTADO' }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    {{-- 3. DATOS DE CLIENTE / FECHAS --}}
    <table class="header-table" style="background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 10px;">
        <tr>
            <td style="width: 50%;">
                <span class="info-label">Cliente:</span><br>
                <span class="bold">{{ $client->name }}</span><br>
                {{ $clientTaxLabel }}: {{ $client->tax_id ?? 'N/A' }}<br>
                Tel: {{ $client->phone ?? 'S/N' }}
            </td>
            <td style="width: 25%;">
                <span class="info-label">Vendedor:</span><br>
                <span>{{ $sale->user->name ?? 'Sistema' }}</span><br>
                <span class="info-label">Fecha:</span><br>
                <span>{{ $sale->created_at->format('d/m/Y g:i A') }}</span>
            </td>
            <td style="width: 25%;" class="text-right">
                @if($vencimiento)
                    <span class="info-label" style="color: #e53e3e;">Vencimiento Pago:</span><br>
                    <span class="bold" style="color: #e53e3e;">{{ $vencimiento }}</span>
                @endif
            </td>
        </tr>
    </table>

    {{-- 4. TABLA DE PRODUCTOS --}}
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" width="10%">Cant.</th>
                <th style="text-align: left;">Descripción</th>
                <th class="text-right" width="20%">Precio Unit.</th>
                <th class="text-right" width="20%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td class="text-center">{{ (int)$item->quantity }}</td>
                    <td>
                        {{ $item->product->name }}
                        @if($item->product->sku)
                            <br><small style="color: #666;">SKU: {{ $item->product->sku }}</small>
                        @endif
                    </td>
                    <td class="text-right">{{ $currency }}{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ $currency }}{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- 5. TOTALES Y DESGLOSE --}}
    <div style="width: 100%; margin-top: 20px;">
        {{-- Lado izquierdo: Datos de pago --}}
        <div style="width: 45%; float: left; background: #f9f9f9; padding: 15px; border-radius: 5px;">
            @if($sale->payment_type === 'cash')
                <span class="info-label">Detalle de Pago:</span><br>
                <table style="width: 100%; margin-top: 5px;">
                    <tr>
                        <td>Efectivo Entregado:</td>
                        <td class="text-right bold">{{ $currency }}{{ number_format($sale->cash_received ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Cambio:</td>
                        <td class="text-right bold">{{ $currency }}{{ number_format($sale->cash_change ?? 0, 2) }}</td>
                    </tr>
                </table>
            @else
                <div style="text-align: center; padding-top: 10px;">
                    <div style="border-top: 1px solid #333; margin-top: 40px; width: 80%; margin-left: auto; margin-right: auto;"></div>
                    <span class="bold" style="font-size: 10px;">FIRMA CONFORME CLIENTE</span>
                </div>
            @endif
        </div>

        {{-- Lado derecho: Totales --}}
        <div class="totals-container">
            <table style="width: 100%;">
                <tr>
                    <td class="info-label">Subtotal Neto:</td>
                    <td class="text-right bold">{{ $currency }}{{ number_format($subtotalCalculado, 2) }}</td>
                </tr>
                <tr>
                    <td class="info-label">{{ $taxName }}:</td>
                    <td class="text-right bold">{{ $currency }}{{ number_format($taxCalculado, 2) }}</td>
                </tr>
                <tr class="grand-total">
                    <td class="bold">TOTAL:</td>
                    <td class="text-right bold" style="font-size: 20px;">{{ $currency }}{{ number_format($sale->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer-notes">
        <p>Esta factura ha sido generada por el sistema de ventas. Gracias por su compra en <strong>{{ $config->nombre_empresa }}</strong>.</p>
        @if($sale->notes)
            <p><strong>Notas:</strong> {{ $sale->notes }}</p>
        @endif
    </div>

</body>
</html>