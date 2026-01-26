@props([
    'options' => [10, 20, 50, 100],
    'formId'  => '' {{-- Valor por defecto si no se envía --}}
])

<div class="flex items-center gap-2 text-sm text-gray-600">
    <label for="per_page_selector" class="font-medium whitespace-nowrap">Mostrar:</label>
    <select 
        name="per_page" 
        id="per_page_selector"
        form="{{ $formId }}" {{-- AQUÍ: Ahora usa la variable dinámica --}}
        class="border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1.5 pl-3 pr-8 transition-all shadow-sm"
    >
        @foreach($options as $value)
            <option value="{{ $value }}" @selected(request('per_page') == $value)>
                {{ $value }}
            </option>
        @endforeach
    </select>
</div>