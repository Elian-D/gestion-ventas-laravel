<x-config-layout>
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">
        Panel de Configuración
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <a href="{{ route('configuration.documentos.index') }}"
           class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
            <h2 class="text-lg font-medium text-gray-800">
                Tipos de documentos
            </h2>
            <p class="text-sm text-gray-500 mt-2">
                Configura los tipos de documentos del sistema.
            </p>
        </a>

        <a href="{{ route('configuration.estados.index') }}"
           class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
            <h2 class="text-lg font-medium text-gray-800">
                Estados de clientes
            </h2>
            <p class="text-sm text-gray-500 mt-2">
                Configura los estados de los clientes.
            </p>
        </a>

        {{-- FUTURAS TARJETAS --}}
    </div>
</x-config-layout>
