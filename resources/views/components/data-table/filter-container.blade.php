@props(['formId'])

<div class="mb-6 bg-white p-1">
    <form id="{{ $formId }}" method="GET" x-on:submit.prevent>
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            {{ $slot }}
        </div>
    </form>

    {{-- Contenedor de Chips --}}
    <div id="active-filters" class="flex flex-wrap items-center gap-2 mt-4">
        // Generado por js
    </div>

</div>