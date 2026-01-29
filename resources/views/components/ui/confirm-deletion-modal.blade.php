{{-- resources/views/components/ui/confirm-deletion-modal.blade.php --}}
<x-modal name="confirm-deletion-{{ $id }}" maxWidth="md">
    <form method="POST" action="{{ $route }}" class="p-6">
        @csrf
        @method($method)

        <div class="flex items-center gap-4 mb-4 text-red-600">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <x-heroicon-s-exclamation-triangle class="w-7 h-7" />
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 leading-tight">{{ $title }}</h2>
                <p class="text-sm text-gray-500 font-medium italic">Confirmar acción</p>
            </div>
        </div>

        <div class="space-y-3">
            <p class="text-sm text-gray-600 leading-relaxed">
                @if($description)
                    {{-- Si pasas una descripción personalizada, se usa aquí --}}
                    {!! $description !!}
                @else
                    {{-- Comportamiento por defecto --}}
                    {{ $getFormattedType() }} 
                    <span class="font-bold text-gray-900 px-1 bg-gray-100 rounded border border-gray-200">
                        {{ $itemName }}
                    </span> 
                    será movido a la <span class="text-amber-600 font-semibold italic">papelera de reciclaje</span>.
                @endif
            </p>

            @if($slot->isNotEmpty())
                <div class="mt-4 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-r-xl shadow-sm">
                    <div class="flex gap-3">
                        <x-heroicon-s-information-circle class="w-5 h-5 text-amber-600 flex-shrink-0" />
                        <div class="text-[12px] text-amber-800 leading-snug">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-8 flex justify-end items-center gap-3">
            <x-secondary-button x-on:click="$dispatch('close')" class="border-none shadow-none hover:bg-gray-100 text-gray-500">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-danger-button type="submit" class="px-5 shadow-lg shadow-red-100">
                <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                {{ __('Confirmar') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>