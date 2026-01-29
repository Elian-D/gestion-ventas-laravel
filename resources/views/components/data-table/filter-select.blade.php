@props([
    'label', 
    'name', 
    'formId' => ''
])

<div>
    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
        {{ $label }}
    </label>

    <select 
        name="{{ $name }}" 
        @if($formId) form="{{ $formId }}" @endif
        class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-colors"
    >
        {{ $slot }}
    </select>
</div>