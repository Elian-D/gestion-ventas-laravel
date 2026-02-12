<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FAC - {{ $invoice->invoice_number }}</title>
    <style>
        /* RESET TOTAL PARA IMPRESIÓN */
        @media print {
            .no-print { display: none !important; }
            body, html { 
                background-color: white !important; 
                margin: 0 !important; 
                padding: 0 !important; 
                width: 100%;
            }
            .print-container { 
                box-shadow: none !important; 
                margin: 0 !important; 
                padding: 0 !important;
                width: 100% !important;
            }
        }

        /* VISTA EN PANTALLA */
        body { 
            background-color: #525659; /* Fondo tipo visor PDF */
            display: flex; 
            justify-content: center; 
            margin: 0;
            padding: 20px;
            font-family: sans-serif;
        }
        .print-container { 
            background: white; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.3); 
            padding: 0;
        }
        .no-print {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 100;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 12px 24px; cursor: pointer; background: #4f46e5; color: white; border: none; border-radius: 6px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
            IMPRIMIR FACTURA
        </button>
        <button onclick="window.close()" style="padding: 12px 24px; cursor: pointer; background: #6b7280; color: white; border: none; border-radius: 6px; margin-left: 10px; font-weight: bold;">
            CERRAR
        </button>
    </div>

    <div class="print-container">
        @include('sales.invoices.formats.' . ($invoice->format_type === 'route' ? 'ticket' : $invoice->format_type))
    </div>

    <script>
        window.onload = function() {
            // Un pequeño delay ayuda a que los estilos carguen bien antes de disparar el print
            setTimeout(() => {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>