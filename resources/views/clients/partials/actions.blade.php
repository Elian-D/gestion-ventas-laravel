<div class="flex items-center gap-3 mt-2 md:mt-0">
    {{-- BOTÓN RADICAL: VER TODO (MODAL) --}}

    {{-- Toggle Activo --}}
    <form action="{{ route('clients.toggle', $client) }}" method="POST">
        @csrf @method('PATCH')
        <button type="submit" class="text-xs px-2 py-1 rounded border {{ $client->active ? 'bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100' : 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' }}">
            {{ $client->active ? 'Deshabilitar' : 'Habilitar' }}
        </button>
    </form>

    <button @click="$dispatch('open-modal', 'view-client-{{ $client->id }}')" 
            class="bg-gray-100 text-gray-600 hover:bg-indigo-600 hover:text-white p-2 rounded-full transition-all shadow-sm"
            title="Ver detalles completos">
        <x-heroicon-s-eye class="w-5 h-5" />
    </button>

    <a href="{{ route('clients.edit', $client) }}" class="text-indigo-600 hover:text-indigo-900 p-2 rounded-full hover:bg-indigo-50">
        <x-heroicon-s-pencil class="w-5 h-5" />
    </a>

    <button @click="$dispatch('open-modal', 'confirm-deletion-{{ $client->id }}')" class="text-red-600 hover:text-red-900 p-2 rounded-full hover:bg-red-50">
        <x-heroicon-s-trash class="w-5 h-5" />
    </button>
</div>