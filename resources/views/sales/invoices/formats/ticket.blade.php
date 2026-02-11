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

    $taxName = $impuestoConfig->nombre ?? 'ITBIS';
    
    // Vencimiento de factura (Crédito comercial)
    $vencimientoPago = $sale->payment_type === 'credit' 
        ? $sale->created_at->addDays($client->credit_limit_days ?? 30)->format('d/m/Y') 
        : null;

    // Lógica de NCF y su Vencimiento Fiscal
    $ncfLog = $sale->ncfLog;
    $vencimientoNcf = $ncfLog?->sequence?->expiry_date 
        ? $ncfLog->sequence->expiry_date->format('d/m/Y') 
        : null;
@endphp

<!DOCTYPE html>
<html>
<head>
    <style>
        * { font-family: 'Courier New', Courier, monospace; font-size: 12px; line-height: 1.2; }
        .ticket { width: 80mm; margin: 0 auto; padding: 5px; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .border-top { border-top: 1px dashed #000; margin-top: 5px; padding-top: 5px; }
        table { width: 100%; border-collapse: collapse; }
        .header-info { font-size: 11px; }
        .signature-box { margin-top: 20px; text-align: center; font-size: 10px; }
        .line { border-top: 1px solid #000; width: 80%; margin: 40px auto 5px auto; }
        /* Estilo para que el eNCF/NCF no rompa el diseño pero resalte */
        .ncf-label { font-size: 13px; letter-spacing: 1px; }
    </style>
</head>
<body>
    <div class="ticket">
        {{-- 1. ENCABEZADO EMPRESA --}}
        <div class="center">
            <span class="bold" style="font-size: 16px;">{{ $config->nombre_empresa }}</span><br>
            <span class="header-info">
                {{ $config->direccion }}<br>
                Tel: {{ $config->telefono }}<br>
                {{ $taxLabel }}: {{ $config->tax_id }}<br>
                <b style="font-size: 9px;">COMPROBANTE AUTORIZADO POR LA DGII</b>
            </span>
        </div>

        {{-- 2. DATOS FISCALES (NCF) --}}
        <div class="border-top">
            <table class="header-info">
                <tr>
                    <td>Factura: <b>{{ $invoice->invoice_number }}</b></td>
                    <td class="right"><b>{{ $sale->payment_type === 'cash' ? 'CONTADO' : 'CRÉDITO' }}</b></td>
                </tr>
                
                @if($sale->ncf)
                <tr>
                    <td colspan="2">
                        {{-- Identificación si es e-NCF o NCF --}}
                        <span>{{ $ncfLog?->type?->is_electronic ? 'e-NCF:' : 'NCF:' }}</span> 
                        <span class="ncf-label">{{ $sale->ncf }}</span>
                    </td>
                </tr>
                @if($vencimientoNcf)
                <tr>
                    <td colspan="2">
                        Vencimiento NCF: <span>{{ $vencimientoNcf }}</span>
                    </td>
                </tr>
                @endif
                @endif
            </table>
        </div>

        {{-- 3. DATOS DEL CLIENTE --}}
        <div class="border-top header-info">
            Cliente: {{ $client->name }}<br>
            @if($client->tax_id)
                RNC/Céd: {{ $client->tax_id }}<br>
            @endif
            Vendedor: {{ $sale->user->name ?? 'Sistema' }}<br>
            Fecha: {{ $sale->created_at->format('d/m/Y g:i A') }}<br>
            
            @if($vencimientoPago)
                <span class="bold">VENCE PAGO: {{ $vencimientoPago }}</span><br>
            @endif
        </div>

        {{-- 4. DETALLE DE PRODUCTOS --}}
        <div class="border-top">
            <table>
                <thead>
                    <tr>
                        <th align="left">CANT</th>
                        <th align="left">DESC.</th>
                        <th class="right">SUBT.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                        <tr>
                            <td valign="top">{{ (int)$item->quantity }}</td>
                            <td valign="top">
                                {{ substr($item->product->name, 0, 25) }}<br>
                                <small>@ {{ $currency }}{{ number_format($item->unit_price, 2) }}</small>
                            </td>
                            <td class="right" valign="top">{{ $currency }}{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 5. TOTALES --}}
        <div class="border-top">
            @php
                $subtotalCalculado = $sale->items->sum('subtotal'); 
                $taxCalculado = $sale->tax > 0 ? $sale->tax : ($sale->total_amount - $subtotalCalculado);
            @endphp
            <table>
                <tr>
                    <td class="header-info">Subtotal Neto:</td>
                    <td class="right">{{ $currency }}{{ number_format($subtotalCalculado, 2) }}</td>
                </tr>
                <tr>
                    <td class="header-info">{{ $taxName }}:</td>
                    <td class="right">{{ $currency }}{{ number_format($taxCalculado, 2) }}</td>
                </tr>
                <tr style="font-size: 14px;">
                    <td class="bold">TOTAL</td>
                    <td class="right bold">{{ $currency }}{{ number_format($sale->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        {{-- 6. PAGO O FIRMA --}}
        @if($sale->payment_type === 'cash')
            <div class="border-top header-info">
                <table>
                    <tr>
                        <td>Efectivo Recibido:</td>
                        <td class="right">{{ $currency }}{{ number_format($sale->cash_received ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="bold">Cambio a Devolver:</td>
                        <td class="right bold">{{ $currency }}{{ number_format($sale->cash_change ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>
        @else
            <div class="signature-box border-top">
                <p>Acepto los términos de pago y recibo de mercancía.</p>
                <div class="line"></div>
                <span class="bold">FIRMA DEL CLIENTE</span>
            </div>
        @endif

        <div class="center border-top" style="margin-top: 10px; font-size: 10px;">
            *** GRACIAS POR SU COMPRA ***<br>
            {{ $config->nombre_empresa }}
        </div>
    </div>
</body>
</html>