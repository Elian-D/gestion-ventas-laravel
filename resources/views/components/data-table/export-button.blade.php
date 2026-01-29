@props([
    'route',            // La ruta de exportación: route('clients.export')
    'formId',           // El ID del formulario de filtros: 'clients-filters'
    'filename' => 'archivo'
])

<div x-data="{ open: false }" class="relative inline-block text-left">
    <button @click="open = !open" 
            type="button" 
            title="Exportar datos"
            class="inline-flex items-center p-2 border border-emerald-200 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition shadow-sm">
        <x-heroicon-s-arrow-down-tray class="w-5 h-5" />
    </button>

    <div x-show="open" 
        x-cloak
        @click.away="open = false" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        class="origin-top-right absolute right-0 mt-2 w-48 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-[100] p-2 border border-gray-100">
        
        <div class="px-3 py-2 border-b border-gray-50 mb-1">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Exportar en formato</span>
        </div>

        <button type="button" 
            @click="
                const form = document.getElementById('{{ $formId }}');
                const params = form ? new URLSearchParams(new FormData(form)).toString() : '';
                window.location.href = '{{ $route }}' + (params ? '?' + params : '');
                open = false;
            "
            class="flex items-center w-full px-3 py-2 text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg transition group">
            <x-heroicon-s-document-text class="w-4 h-4 mr-3 text-emerald-400 group-hover:text-emerald-500" />
            Excel (.xlsx)
        </button>
    </div>
</div>