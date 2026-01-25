@props(['options' => [10, 20, 50, 100]])

<div class="flex items-center gap-2 text-sm text-gray-600">
    <label for="per_page" class="font-medium whitespace-nowrap">Mostrar:</label>
    <select 
        name="per_page" 
        id="per_page_selector"
        form="clients-filters" {{-- Vinculación mágica con tu formulario de filtros --}}
        class="border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1.5 pl-3 pr-8 transition-all shadow-sm"
    >
        @foreach($options as $value)
            <option value="{{ $value }}" @selected(request('per_page') == $value)>
                {{ $value }}
            </option>
        @endforeach
    </select>
</div>