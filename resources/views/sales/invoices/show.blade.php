<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Principal --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex-1">
                    <a href="{{ route('sales.invoices.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 text-sm font-semibold transition mb-3">
                        <x-heroicon-s-arrow-left class="w-4 h-4 mr-1.5" />
                        Regresar al Historial
                    </a>
                    <div class="flex items-center gap-3">
                        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                            Factura <span class="text-indigo-600">{{ $invoice->invoice_number }}</span>
                        </h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $invoice->status === 'active' ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-600/20' : 'bg-red-100 text-red-700 ring-1 ring-red-600/20' }}">
                            {{ $invoice->status === 'active' ? 'Vigente' : 'Anulada' }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('sales.invoices.print', $invoice) }}" target="_blank" 
                       class="inline-flex items-center px-5 py-2.5 bg-gray-900 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-800 shadow-lg active:scale-95 transition-all">
                        <x-heroicon-s-printer class="w-4 h-4 mr-2" />
                        Imprimir Documento
                    </a>
                    
                    @if($invoice->format_type === 'letter')
                    <a href="{{ route('sales.invoices.print', $invoice) }}?download=1" 
                       class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-300 rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 active:scale-95 transition-all">
                        <x-heroicon-s-arrow-down-tray class="w-4 h-4 mr-2" />
                        PDF
                    </a>
                    @endif
                </div>
            </div>

            {{-- Grid de Contenido --}}
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
                
                {{-- Lateral: Información y Auditoría (1 Columna) --}}
                <div class="xl:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-5 py-4 border-b border-gray-100">
                            <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest">Información General</h3>
                        </div>
                        <div class="p-5 space-y-5">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Cliente</label>
                                <p class="text-sm font-bold text-gray-800 leading-tight">{{ $invoice->sale->client->name }}</p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Venta de Origen</label>
                                <p class="text-sm font-bold text-indigo-600">#{{ $invoice->sale->number }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Formato</label>
                                    <p class="text-xs font-bold text-gray-600 capitalize">{{ $invoice->format_type }}</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Condición</label>
                                    <p class="text-xs font-bold text-gray-600 capitalize">{{ $invoice->type }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-indigo-600 rounded-2xl p-5 shadow-md text-white">
                        <h4 class="text-[10px] font-black uppercase tracking-widest mb-3 opacity-80">Auditoría del Sistema</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-[11px] opacity-70 italic">Generado:</span>
                                <span class="text-xs font-bold">{{ $invoice->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[11px] opacity-70 italic">Usuario:</span>
                                <span class="text-xs font-bold">{{ $invoice->generated_by }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Central: Preview con iframe --}}
                <div class="xl:col-span-3">
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl p-4 md:p-8 border border-gray-300 shadow-inner flex flex-col items-center justify-start min-h-[800px] overflow-auto">
                        
                        {{-- Contenedor dinámico --}}
                        <div class="invoice-frame-container {{ $invoice->format_type === 'letter' ? 'is-letter' : 'is-ticket' }}">
                            <div class="bg-white shadow-2xl ring-1 ring-black/5 rounded-lg overflow-hidden h-full">
                                {{-- 
                                Añadimos loading="lazy" para la percepción de carga 
                                y forzamos un fondo blanco mientras carga 
                                --}}
                                <iframe 
                                    src="{{ route('sales.invoices.preview', $invoice) }}" 
                                    class="w-full h-full border-0 bg-white"
                                    loading="lazy"
                                    id="invoice-iframe"
                                    title="Preview de Factura">
                                </iframe>
                            </div>
                        </div>

                    </div>
                </div>

                <style>
                    /* Contenedor Base */
                    .invoice-frame-container {
                        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                        background: white;
                    }

                    /* Estilos para CARTA (Letter) */
                    .is-letter {
                        width: 21.59cm; /* Ancho real carta */
                        height: 27.94cm; /* Alto real carta */
                        transform: scale(0.7); /* Reducimos para que quepa en pantalla de laptop */
                        transform-origin: top center;
                    }

                    /* Estilos para TICKET (80mm o 58mm) */
                    .is-ticket {
                        width: 85mm; 
                        min-height: 150mm;
                        height: 600px; /* Altura fija para scroll interno del iframe */
                    }

                    /* Ajustes Responsivos para que no se salga de la pantalla */
                    @media (max-width: 1280px) {
                        .is-letter { transform: scale(0.5); }
                        .is-ticket { transform: scale(0.9); }
                    }

                    @media (min-width: 1536px) {
                        .is-letter { transform: scale(0.85); }
                        .is-ticket { transform: scale(1.2); }
                    }
                </style>
            </div>
        </div>
    </div>

    <style>
        /* Escalado responsivo según tipo de documento */
        .invoice-frame-container {
            transition: transform 0.3s ease;
            transform-origin: top center;
        }

        /* Pantallas pequeñas: Reducido */
        @media (max-width: 640px) {
            .scale-ticket {
                transform: scale(0.85);
            }
        }

        /* Pantallas medianas: Normal */
        @media (min-width: 641px) and (max-width: 1279px) {
            .scale-ticket {
                transform: scale(1);
            }
        }

        /* Pantallas grandes: Ampliado */
        @media (min-width: 1280px) {
            .scale-ticket {
                transform: scale(1.3);
            }
        }

        /* Pantallas extra grandes: Más ampliado */
        @media (min-width: 1536px) {
            .scale-ticket {
                transform: scale(1.5);
            }
        }
    </style>
</x-app-layout>