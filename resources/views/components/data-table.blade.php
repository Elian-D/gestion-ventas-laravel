@props([
    'items', // La colección paginada (aunque no la iteraremos aquí, la necesitamos para la paginación)
    'headers', // El array de encabezados
])

<div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
    <table class="min-w-full divide-y divide-gray-200">
        
        {{-- ENCABEZADOS --}}
        <thead class="bg-gray-50 hidden md:table-header-group">
            <tr>
                @foreach($headers as $header)
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $header }}
                    </th>
                @endforeach
                {{-- La columna de acciones se convierte en otro header --}}
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        
        {{-- CUERPO: El slot sin nombre contiene todas las filas (<tr>) generadas por la vista --}}
        <tbody class="bg-white divide-y divide-gray-200 md:table-row-group">
            {{ $slot }}
        </tbody>
    </table>
</div>

{{-- PAGINACIÓN (Sigue siendo útil) --}}
@if(method_exists($items, 'links'))
    <div class="mt-6">
        {{ $items->links() }}
    </div>
@endif