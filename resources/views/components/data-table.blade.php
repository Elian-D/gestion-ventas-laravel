@props([
    'items',
    'headers',
])

<div class="overflow-x-auto border border-gray-200 rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50 hidden md:table-header-group">
            <tr>
                @foreach($headers as $header)
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        {{ $header }}
                    </th>
                @endforeach
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
            {{ $slot }}
        </tbody>
    </table>
</div>

@if(method_exists($items, 'links'))
    <div class="mt-4 pagination">
        {{ $items->links() }}
    </div>
@endif
