@props([
    'allColumns' => [],
    'visibleColumns' => [],
    'defaultVisible' => [], // Nueva prop: la lista del controlador
    'formId' => ''
])

<div x-data="{ open: false }" 
     id="column-selector-container"
     data-default-columns='@json($defaultVisible)'
     class="relative inline-block text-left w-full sm:w-auto">
    
    <button @click="open = !open" type="button" class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
        <x-heroicon-s-view-columns class="w-4 h-4 mr-2" />
        Columnas
        <x-heroicon-s-chevron-down class="ml-2 w-4 h-4" />
    </button>

    <div x-show="open" @click.away="open = false" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        class="origin-top-right absolute right-0 mt-2 w-64 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-[70] p-2">

        <div class="px-3 py-2 border-b border-gray-100 mb-1 flex justify-between items-center">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Configurar tabla</span>
            
            <button type="button" 
                onclick="window.resetTableColumns()"
                class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 uppercase transition">
                Restablecer
            </button>
        </div>

        <div class="max-h-64 overflow-y-auto">
            @foreach($allColumns as $key => $label)
                <label class="flex items-center px-3 py-2 hover:bg-indigo-50 rounded-lg cursor-pointer group transition">
                    <input type="checkbox" 
                        name="columns[]" 
                        value="{{ $key }}" 
                        data-column-key="{{ $key }}" {{-- Marcador para el JS --}}
                        @if($formId) form="{{ $formId }}" @endif
                        @checked(in_array($key, $visibleColumns))
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                    <span class="text-sm text-gray-600 group-hover:text-indigo-700 font-medium transition">
                        {{ $label }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>
</div>