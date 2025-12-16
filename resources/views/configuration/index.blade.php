<x-config-layout>
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-800">
            Panel de Configuración
        </h1>
        <p class="text-gray-500 mt-2">
            Administra las configuraciones generales del sistema
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Tipos de documentos -->
        <a href="{{ route('configuration.documentos.index') }}"
           class="group bg-white border border-gray-200 p-6 rounded-xl
                  shadow-sm hover:shadow-lg transition-all">

            <div class="flex items-center gap-4">
                <div class="p-3 rounded-lg bg-indigo-100 text-indigo-600
                            group-hover:bg-indigo-600 group-hover:text-white transition">
                    <x-heroicon-s-identification class="w-6 h-6" />
                </div>

                <h2 class="text-lg font-semibold text-gray-800">
                    Tipos de documentos
                </h2>
            </div>

            <p class="mt-4 text-sm text-gray-500">
                Configura los tipos de documentos utilizados en el sistema.
            </p>
        </a>

        <!-- Estados de clientes -->
        <a href="{{ route('configuration.estados.index') }}"
           class="group bg-white border border-gray-200 p-6 rounded-xl
                  shadow-sm hover:shadow-lg transition-all">

            <div class="flex items-center gap-4">
                <div class="p-3 rounded-lg bg-emerald-100 text-emerald-600
                            group-hover:bg-emerald-600 group-hover:text-white transition">
                    <x-heroicon-s-user class="w-6 h-6" />
                </div>

                <h2 class="text-lg font-semibold text-gray-800">
                    Estados de clientes
                </h2>
            </div>

            <p class="mt-4 text-sm text-gray-500">
                Define y administra los estados de los clientes.
            </p>
        </a>

        <!-- Días de la semana -->
        <a href="{{ route('configuration.dias.index') }}"
           class="group bg-white border border-gray-200 p-6 rounded-xl
                  shadow-sm hover:shadow-lg transition-all">

            <div class="flex items-center gap-4">
                <div class="p-3 rounded-lg bg-amber-100 text-amber-600
                            group-hover:bg-amber-600 group-hover:text-white transition">
                    <x-heroicon-s-calendar-days class="w-6 h-6" />
                </div>

                <h2 class="text-lg font-semibold text-gray-800">
                    Días de la semana
                </h2>
            </div>

            <p class="mt-4 text-sm text-gray-500">
                Configura los estados y disponibilidad de los días.
            </p>
        </a>

    </div>
</x-config-layout>
