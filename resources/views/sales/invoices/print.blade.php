<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Imprimir Factura - {{ $invoice->invoice_number }}</title>
    <style>
        /* Estilos para ocultar todo excepto el contenido al imprimir */
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 0; }
        }
        body { background-color: #f3f4f6; display: flex; justify-content: center; padding: 20px; }
        .print-container { background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="no-print" style="position: fixed; top: 20px; left: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #4f46e5; color: white; border: none; border-radius: 5px;">
            Imprimir Ahora
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; background: #ef4444; color: white; border: none; border-radius: 5px; margin-left: 10px;">
            Cerrar
        </button>
    </div>

    <div class="print-container">
        {{-- Cargamos dinámicamente el formato ticket o ruta --}}
        @include('sales.invoices.formats.' . ($invoice->format_type === 'route' ? 'ticket' : $invoice->format_type))
    </div>

    <script>
        // Disparar el diálogo de impresión automáticamente al cargar
        window.onload = function() {
            window.print();
            // Opcional: Cerrar la ventana después de imprimir/cancelar
            // window.onafterprint = function() { window.close(); }
        }
    </script>
</body>
</html>