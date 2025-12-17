<x-config-layout>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            
            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">
                    <x-heroicon-s-cog-6-tooth class="w-8 h-8 inline mr-3 text-indigo-600" />
                    Configuración General
                </h1>
                <p class="text-gray-600">Gestiona la información clave de tu empresa, finanzas y localización</p>
            </div>

            {{-- MENSAJES DE ESTADO --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 rounded-lg p-4 flex items-center gap-3">
                    <x-heroicon-s-check-circle class="w-5 h-5 text-emerald-600 flex-shrink-0" />
                    <p class="text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 rounded-lg p-4 flex items-center gap-3">
                    <x-heroicon-s-exclamation-circle class="w-5 h-5 text-rose-600 flex-shrink-0" />
                    <p class="text-rose-800">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('configuration.general.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- SECCIÓN 1: DATOS DE LA EMPRESA --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                            <x-heroicon-s-building-library class="w-6 h-6" />
                            Información de la Empresa
                        </h2>
                    </div>

                    <div class="p-6 space-y-6">
                        {{-- Nombre de la Empresa --}}
                        <div>
                            <x-input-label for="nombre_empresa" value="Nombre de la Empresa" class="font-semibold" />
                            <x-text-input
                                id="nombre_empresa"
                                name="nombre_empresa"
                                type="text"
                                class="mt-2 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('nombre_empresa', $configuracionGeneral->nombre_empresa ?? '') }}"
                                required
                            />
                            <x-input-error :messages="$errors->get('nombre_empresa')" class="mt-2" />
                        </div>

                        {{-- LOGO --}}
                        <div x-data="{ photoName: null, photoPreview: null }" class="border-t pt-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Logo de la Empresa</h3>
                            <div class="flex flex-col sm:flex-row gap-6">
                                
                                {{-- PREVISUALIZACIÓN --}}
                                <div class="flex flex-col items-center">
                                    <div class="relative">
                                        <img x-show="!photoPreview"
                                            src="{{ $configuracionGeneral->logo ? asset('storage/' . $configuracionGeneral->logo) : asset('images/default-logo.png') }}"
                                            alt="Logo"
                                            class="h-32 w-32 object-contain bg-gray-50 rounded-xl border-2 border-gray-200 p-2">
                                        
                                        <img x-show="photoPreview"
                                            x-bind:src="photoPreview"
                                            class="h-32 w-32 object-contain bg-gray-50 rounded-xl border-2 border-indigo-400 p-2"
                                            style="display: none;">
                                        
                                        <span x-show="photoPreview" class="absolute -top-2 -right-2 bg-indigo-600 text-white rounded-full p-1">
                                            <x-heroicon-s-check class="w-4 h-4" />
                                        </span>
                                    </div>
                                </div>

                                {{-- CAMPO DE SUBIDA --}}
                                <div class="flex-1 flex flex-col justify-center">
                                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Subir Logo
                                    </label>
                                    <div class="relative">
                                        <input type="file"
                                            id="logo"
                                            name="logo"
                                            class="hidden"
                                            accept=".jpeg,.png,.jpg,.gif"
                                            x-ref="logo"
                                            @change="
                                                photoName = $refs.logo.files[0].name;
                                                const reader = new FileReader();
                                                reader.onload = (e) => {
                                                    photoPreview = e.target.result;
                                                };
                                                reader.readAsDataURL($refs.logo.files[0]);
                                            "
                                        />
                                        <button type="button"
                                            @click="$refs.logo.click()"
                                            class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition text-gray-700 font-medium flex items-center justify-center gap-2">
                                            <x-heroicon-s-arrow-up-tray class="w-5 h-5" />
                                            Seleccionar archivo
                                        </button>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">PNG, JPG, GIF • Máx 2MB</p>
                                    <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        {{-- Contacto --}}
                        <div class="border-t pt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="telefono" value="Teléfono" class="font-semibold" />
                                <x-text-input
                                    id="telefono"
                                    name="telefono"
                                    type="tel"
                                    class="mt-2 block w-full rounded-lg border-gray-300"
                                    value="{{ old('telefono', $configuracionGeneral->telefono ?? '') }}"
                                    placeholder="+1 (555) 123-4567"
                                />
                                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="email" value="Email de Contacto" class="font-semibold" />
                                <x-text-input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="mt-2 block w-full rounded-lg border-gray-300"
                                    value="{{ old('email', $configuracionGeneral->email ?? '') }}"
                                    placeholder="contacto@empresa.com"
                                />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Ubicación --}}
                        <div class="border-t pt-6 space-y-4">
                            <div>
                                <x-input-label for="direccion" value="Dirección" class="font-semibold" />
                                <textarea
                                    id="direccion"
                                    name="direccion"
                                    rows="3"
                                    class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Calle, número, apartamento..."
                                >{{ old('direccion', $configuracionGeneral->direccion ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="ciudad" value="Ciudad" class="font-semibold" />
                                    <x-text-input
                                        id="ciudad"
                                        name="ciudad"
                                        type="text"
                                        class="mt-2 block w-full rounded-lg border-gray-300"
                                        value="{{ old('ciudad', $configuracionGeneral->ciudad ?? '') }}"
                                    />
                                    <x-input-error :messages="$errors->get('ciudad')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="pais" value="País" class="font-semibold" />
                                    <x-text-input
                                        id="pais"
                                        name="pais"
                                        type="text"
                                        class="mt-2 block w-full rounded-lg border-gray-300"
                                        value="{{ old('pais', $configuracionGeneral->pais ?? '') }}"
                                    />
                                    <x-input-error :messages="$errors->get('pais')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 2: CONFIGURACIÓN FINANCIERA --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                            <x-heroicon-s-banknotes class="w-6 h-6" />
                            Configuración Financiera
                        </h2>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            {{-- Moneda Base --}}
                            <div>
                                <x-input-label for="moneda_id" value="Moneda Base" class="font-semibold" />
                                <select
                                    id="moneda_id"
                                    name="moneda_id"
                                    class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                    required
                                >
                                    <option value="">Seleccione Moneda</option>
                                    @foreach($monedas as $moneda)
                                        <option value="{{ $moneda->id }}"
                                            {{ old('moneda_id', $configuracionGeneral->moneda_id ?? '') == $moneda->id ? 'selected' : '' }}>
                                            {{ $moneda->nombre }} ({{ $moneda->simbolo }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('moneda_id')" class="mt-2" />
                            </div>

                            {{-- Impuesto Base --}}
                            <div>
                                <x-input-label for="impuesto_id" value="Impuesto Principal" class="font-semibold" />
                                <select
                                    id="impuesto_id"
                                    name="impuesto_id"
                                    class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                    required
                                >
                                    <option value="">Seleccione Impuesto</option>
                                    @foreach($impuestos as $impuesto)
                                        <option value="{{ $impuesto->id }}"
                                            {{ old('impuesto_id', $configuracionGeneral->impuesto_id ?? '') == $impuesto->id ? 'selected' : '' }}>
                                            {{ $impuesto->nombre }} ({{ $impuesto->valor }}@if($impuesto->tipo === 'porcentaje')%@endif)
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('impuesto_id')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Timezone --}}
                        <div class="border-t pt-6">
                            <x-input-label for="timezone" value="Zona Horaria" class="font-semibold" />
                            <x-text-input
                                id="timezone"
                                name="timezone"
                                type="text"
                                class="mt-2 block w-full rounded-lg border-gray-300"
                                value="{{ old('timezone', $configuracionGeneral->timezone ?? 'America/Santo_Domingo') }}"
                                placeholder="America/Santo_Domingo"
                                required
                            />
                            <p class="mt-2 text-xs text-gray-500">
                                <x-heroicon-s-information-circle class="w-4 h-4 inline mr-1" />
                                Usa un formato válido: America/New_York, Europe/London, etc.
                            </p>
                            <x-input-error :messages="$errors->get('timezone')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- BOTÓN GUARDAR --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('configuration.index') }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300 transition flex items-center gap-2">
                        <x-heroicon-s-arrow-left class="w-5 h-5" />
                        Cancelar
                    </a>
                    <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 rounded-lg px-6 py-3 flex items-center gap-2">
                        <x-heroicon-s-arrow-up-tray class="w-5 h-5" />
                        Guardar Cambios
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>

</x-config-layout>