@props([
    'title',
    'description' => null,
])

<div class="flex flex-col gap-4 mb-6 border-b border-gray-100 pb-4">

    {{-- FILA SUPERIOR --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

        {{-- TÍTULO --}}
        <div class="flex flex-col">
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
                {{ $title }}
            </h2>

            @if($description)
                <p class="text-sm text-gray-500">
                    {{ $description }}
                </p>
            @endif
        </div>

        {{-- ACCIONES --}}
        @isset($actions)
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                {{ $actions }}
            </div>
        @endisset
    </div>

    {{-- SLOT EXTRA (opcional) --}}
    @isset($extra)
        <div class="flex flex-wrap gap-3">
            {{ $extra }}
        </div>
    @endisset
</div>
