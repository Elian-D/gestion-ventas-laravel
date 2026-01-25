<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">

            <div class="fixed top-4 right-4 z-50 flex flex-col gap-4 w-full max-w-sm px-4 md:px-0">
                {{-- TOAST DE ÉXITO --}}
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-8"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        x-init="setTimeout(() => show = false, 5000)"
                        class="overflow-hidden rounded-lg shadow-2xl border border-emerald-600">
                        {{-- Cabecera del Toast (Verde Oscuro) --}}
                        <div class="bg-emerald-600 px-4 py-2 flex justify-between items-center">
                            <span class="text-white font-bold text-sm">Configuración actualizada</span>
                            <div class="flex items-center gap-2">
                                <span class="text-emerald-100 text-xs font-medium">Éxito</span>
                                <button @click="show = false" class="text-white hover:text-emerald-200 transition-colors">
                                    <x-heroicon-s-x-mark class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                        {{-- Cuerpo del Toast (Verde Claro) --}}
                        <div class="bg-emerald-500 px-4 py-3">
                            <p class="text-white text-sm leading-relaxed">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                @endif

                {{-- TOAST DE ERROR --}}
                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-8"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-init="setTimeout(() => show = false, 6000)"
                        class="overflow-hidden rounded-lg shadow-2xl border border-red-600">
                        <div class="bg-red-600 px-4 py-2 flex justify-between items-center">
                            <span class="text-white font-bold text-sm">Error en el sistema</span>
                            <div class="flex items-center gap-2">
                                <span class="text-red-100 text-xs font-medium">Alerta</span>
                                <button @click="show = false" class="text-white hover:text-red-200">
                                    <x-heroicon-s-x-mark class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                        <div class="bg-red-500 px-4 py-3">
                            <p class="text-white text-sm leading-relaxed">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
            </div>

        <x-page-toolbar title="Papelera de Clientes" subtitle="Registros eliminados recientemente">
            <x-slot name="actions">
                <a href="{{ route('clients.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                    Volver al listado
                </a>
            </x-slot>
        </x-page-toolbar>

        <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100 mt-6 p-6">
            <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                <form id="clients-trash-filters" class="flex gap-4">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon-s-magnifying-glass class="h-4 w-4 text-gray-400" />
                        </span>
                        <input type="text" name="search" 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm" 
                                placeholder="Buscar por nombre, RNC o email...">
                    </div>
                </form>
            </div>

            {{-- Contenedor de la Tabla --}}
            <div id="clients-trash-table" class="p-0">
                @include('clients.partials.eliminados-table', ['items' => $items])
            </div>
        </div>
    </div>
</x-app-layout>
