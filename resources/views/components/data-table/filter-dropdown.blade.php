@props(['label' => 'Filtros'])

<div x-data="{ open: false }" class="relative w-full md:w-auto text-left">
    <button @click="open = !open" type="button"
        class="inline-flex justify-center items-center w-full md:w-auto px-4 py-2 border rounded-lg bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition">
        <x-heroicon-s-funnel class="mr-2 h-4 w-4 text-gray-400" />
        {{ $label }}
        <x-heroicon-s-chevron-down class="ml-2 -mr-1 h-4 w-4 text-gray-400" />
    </button>

    <div x-show="open" 
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        class="origin-top-right absolute right-0 mt-2 w-72 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50 p-4 border border-gray-100">
        
        <div class="flex flex-col gap-4">
            {{ $slot }}
        </div>
    </div>
</div>