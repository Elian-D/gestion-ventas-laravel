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
    $taxLabel = $taxIdentifier->code ?? 'ID FISCAL';

    // Identificador fiscal del CLIENTE (RNC/Cédula)
    $clientTaxIdentifier = \DB::table('tax_identifier_types')
                        ->where('id', $client->tax_identifier_type_id)
                        ->first();
    $clientTaxLabel = $clientTaxIdentifier->code ?? 'RNC/CED';

    $taxName = $impuestoConfig->nombre ?? 'Impuesto';
    
    // Vencimiento de factura (Crédito)
    $vencimiento = $sale->payment_type === 'credit' 
        ? $sale->created_at->addDays($client->credit_limit_days ?? 30)->format('d/m/Y') 
        : null;

    /* COLA PARA FUTUROS NCF:
       $ncf = $sale->ncf ?? 'B0100000001'; 
       $vencimientoNcf = '31/12/' . date('Y');
    */
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
        .ncf-section { font-size: 12px; font-weight: bold; margin: 5px 0; }
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
                {{-- Comentado para el futuro: --}}
                {{-- <b>COMPROBANTE AUTORIZADO POR LA DGII</b> --}}
            </span>
        </div>

        {{-- 2. DATOS DE FACTURA Y NCF --}}
        <div class="border-top">
            <table class="header-info">
                <tr>
                    <td>Factura: <b>{{ $invoice->invoice_number }}</b></td>
                    <td class="right"><b>{{ $sale->payment_type === 'credit' ? 'CREDITO' : 'CONTADO' }}</b></td>
                </tr>
                {{-- COLA NCF --}}
                {{-- 
                <tr class="ncf-section">
                    <td>NCF: <b>B0100000001</b></td>
                    <td class="right">Vence: 31/12/2026</td>
                </tr>
                --}}
            </table>
        </div>

        <div class="border-top header-info">
            Cliente: {{ $client->name }}<br>
            {{ $clientTaxLabel }}: {{ $client->tax_id ?? 'N/A' }}<br>
            Vendedor: {{ $sale->user->name ?? 'Sistema' }}<br>
            Fecha: {{ $sale->created_at->format('d/m/Y g:i A') }}<br>
            @if($vencimiento)
                <span class="bold">VENCE (PAGO): {{ $vencimiento }}</span><br>
            @endif
        </div>

        {{-- 3. DETALLE DE PRODUCTOS --}}
        <div>
            <table>
                <thead>
                    <tr class="border-top">
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

        {{-- 4. TOTALES --}}
        <div class="border-top">
            @php
                // Calculamos el subtotal real sumando los subtotales de cada item
                $subtotalCalculado = $sale->items->sum('subtotal'); 
                // El ITBIS lo tomamos de la venta (si ya se calculó) o de la diferencia
                $taxCalculado = $sale->tax > 0 ? $sale->tax : ($sale->total_amount - $subtotalCalculado);
            @endphp
            <table>
                <tr style="font-size: 14px;">
                    <td class="bold">TOTAL</td>
                    <td class="right bold">{{ $currency }}{{ number_format($sale->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="header-info">Subtotal Neto:</td>
                    {{-- Usamos la suma de los items para asegurar que no salga en 0 --}}
                    <td class="right">{{ $currency }}{{ number_format($subtotalCalculado, 2) }}</td>
                </tr>
                <tr>
                    <td class="header-info">{{ $taxName }}:</td>
                    <td class="right">{{ $currency }}{{ number_format($taxCalculado, 2) }}</td>
                </tr>
            </table>
        </div>
        {{-- 5. SECCIÓN DINÁMICA: PAGO O FIRMA --}}
        @if($sale->payment_type === 'cash')
            <div class="border-top">
                <table>
                    <tr>
                        <td>Recibido:</td>
                        <td class="right">{{ $currency }}{{ number_format($sale->cash_received ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="bold">Cambio:</td>
                        <td class="right bold">{{ $currency }}{{ number_format($sale->cash_change ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>
        @else
            <div class="signature-box border-top">
                <p>Recibí conforme los artículos detallados en esta factura y acepto los términos de pago.</p>
                <div class="line"></div>
                <span class="bold">FIRMA DEL CLIENTE</span>
            </div>
        @endif

        <div class="center border-top" style="margin-top: 10px;">
            *** GRACIAS POR SU COMPRA ***<br>
            {{ $config->nombre_empresa }}
        </div>
    </div>
</body>
</html>