@props([
    'placeholder' => 'Buscar...',
    'name' => 'search',
    'formId' => ''
])

<div class="relative w-full md:w-72">
    <button 
        type="button"
        class="absolute inset-y-0 left-0 flex items-center pl-3 cursor-pointer hover:text-indigo-600 transition-colors"
        onclick="this.closest('div').querySelector('input').form.dispatchEvent(new Event('submit'))"
        title="Buscar"
    >
        <x-heroicon-s-magnifying-glass class="w-4 h-4 text-gray-400" />
    </button>
    <input 
        type="text" 
        name="{{ $name }}" 
        @if($formId) form="{{ $formId }}" @endif
        value="{{ request($name) }}" 
        placeholder="{{ $placeholder }}" 
        class="w-full border border-gray-300 rounded-lg pl-9 pr-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition shadow-sm"
    >
</div>