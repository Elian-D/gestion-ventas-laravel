@props(['actions' => []])

<div x-data="{ 
        selectedIds: [], 
        open: false,
        init() {
            document.addEventListener('table-selection-changed', (e) => {
                this.selectedIds = e.detail.ids;
            });
        }
    }" 
    x-show="selectedIds.length > 0"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="relative inline-block text-left"
    style="display: none;"
>
    <button @click="open = !open" type="button" 
        class="inline-flex items-center px-2 py-2 border-2 border-indigo-600 rounded-lg bg-indigo-50 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition shadow-sm">
        <span x-text="selectedIds.length" class="mr-2 bg-indigo-700 text-white px-2 py-0.5 rounded-full text-[10px]"></span>
        Acciones Masivas
        <x-heroicon-s-chevron-down class="ml-2 w-4 h-4" />
    </button>

    <div x-show="open" @click.away="open = false" 
        class="origin-top-left absolute left-0 mt-2 w-56 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-[80] p-2 border border-indigo-100">
        
        <div class="px-3 py-2 border-b border-gray-100 mb-1">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Seleccionar acción</span>
        </div>

        @foreach($actions as $action)
            <button type="button" 
                @click="open = false; $dispatch('execute-bulk-action', { 
                    action: '{{ $action['id'] }}', 
                    label: '{{ $action['label'] }}',
                    ids: selectedIds,
                    requiresValue: {{ isset($action['options']) ? 'true' : 'false' }},
                    options: @js($action['options'] ?? [])
                })"
                class="flex items-center w-full px-3 py-2 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition group">
                @if(isset($action['icon']))
                    <x-dynamic-component :component="$action['icon']" class="w-4 h-4 mr-3 text-gray-400 group-hover:text-indigo-500" />
                @endif
                {{ $action['label'] }}
            </button>
        @endforeach
    </div>
</div>