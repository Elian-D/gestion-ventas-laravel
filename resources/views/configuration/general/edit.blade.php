<x-config-layout>
    {{-- 
        Contenedor Principal con x-data. 
        Se utiliza x-cloak para evitar parpadeos de elementos ocultos al cargar.
    --}}
    <div class="min-h-screen bg-gray-50/50 py-8 px-4 sm:px-6 lg:px-8"
        x-cloak
        x-data="{ 
            countries: {{ $countries->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'emoji' => $c->emoji])->toJson() }},
            states: {{ $states->toJson() }}, 
            
            searchCountry: '',
            searchState: '',
            openCountry: false,
            openState: false,

            selectedCountry: '{{ old('country_id', $config->country_id ?? 62) }}',
            selectedState: '{{ old('state_id', $config->state_id ?? '') }}',
            
            phoneCode: '{{ $countries->where('id', old('country_id', $config->country_id ?? 62))->first()->phonecode ?? '' }}',
            currency: '{{ old('currency', $config->currency ?? '---') }}',
            symbol: '{{ old('currency_symbol', $config->currency_symbol ?? '') }}',
            timezone: '{{ old('timezone', $config->timezone ?? 'UTC') }}',
            isLoading: false,
            errorMessage: '',

            // Helper para búsqueda insensible a tildes y mayúsculas
            formatSearch(text) {
                return text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            },

            get filteredCountries() {
                let s = this.formatSearch(this.searchCountry);
                return this.countries.filter(c => this.formatSearch(c.name).includes(s));
            },

            get filteredStates() {
                let s = this.formatSearch(this.searchState);
                return this.states.filter(s_obj => this.formatSearch(s_obj.name).includes(s));
            },

            // Carga de datos regionales desde API
            async updateRegionalData(id) {
                this.selectedCountry = id;
                this.openCountry = false;
                this.searchCountry = '';
                this.isLoading = true;
                this.errorMessage = '';
                
                try {
                    const response = await fetch(`/api/countries/${id}`);
                    if (!response.ok) throw new Error('Error al conectar con el servidor');
                    const data = await response.json();
                    
                    this.states = data.states;
                    this.currency = data.currency;
                    this.symbol = data.currency_symbol;
                    this.timezone = data.timezone;
                    this.phoneCode = data.phonecode;

                    // Resetear estado si el actual no pertenece al nuevo país
                    if (!this.states.find(s => s.id == this.selectedState)) { 
                        this.selectedState = ''; 
                    }
                } catch (error) { 
                    this.errorMessage = 'No se pudo actualizar la información regional.';
                    console.error(error); 
                } finally { 
                    this.isLoading = false; 
                }
            },

            selectState(state) {
                this.selectedState = state.id;
                this.openState = false;
                this.searchState = '';
                if (state.timezone) { this.timezone = state.timezone; }
            }
        }">
        
        <div class="max-w-5xl mx-auto">
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                        <div class="p-2 bg-indigo-600 rounded-lg shadow-lg shadow-indigo-200">
                            <x-heroicon-s-cog-6-tooth class="w-6 h-6 text-white" />
                        </div>
                        Configuración General
                    </h1>
                </div>
            </div>

            {{-- Alerta de Error Dinámica --}}
            <template x-if="errorMessage">
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-red-700">
                    <div class="flex items-center">
                        <x-heroicon-s-x-circle class="w-5 h-5 mr-2" />
                        <span x-text="errorMessage"></span>
                    </div>
                </div>
            </template>

            @if(session('success'))
                <div class="mb-6 animate-fade-in-down">
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
                        <x-heroicon-s-check-circle class="w-5 h-5 text-emerald-500" />
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('configuration.general.update') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- SECCIÓN: DATOS OCULTOS PARA EL SERVIDOR --}}
                <input type="hidden" name="currency" :value="currency">
                <input type="hidden" name="currency_symbol" :value="symbol">
                <input type="hidden" name="timezone" :value="timezone">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- COLUMNA IZQUIERDA --}}
                    <div class="lg:col-span-1 space-y-6">
                        {{-- Gestión de Logo con Preview e Input File --}}
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                             <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <x-heroicon-s-photo class="w-5 h-5 text-indigo-500" />
                                Identidad
                            </h3>
                            <div x-data="{ photoPreview: null }" class="flex flex-col items-center">
                                <div class="relative group">
                                    <div class="w-40 h-40 rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 overflow-hidden flex items-center justify-center relative">
                                        <template x-if="!photoPreview">
                                            <img src="{{ $config?->logo ? asset('storage/' . $config->logo) : asset('images/default-logo.png') }}" 
                                                 class="max-w-full max-h-full object-contain p-4">
                                        </template>
                                        <template x-if="photoPreview">
                                            <img :src="photoPreview" class="max-w-full max-h-full object-contain p-4">
                                        </template>
                                    </div>
                                    
                                    <label for="logo" class="absolute -bottom-3 -right-3 bg-white shadow-lg border border-gray-100 p-2 rounded-full cursor-pointer hover:scale-110 transition-transform text-indigo-600">
                                        <x-heroicon-s-pencil-square class="w-5 h-5" />
                                        <input type="file" id="logo" name="logo" class="hidden" accept="image/*"
                                               @change="
                                                const file = $event.target.files[0]; 
                                                if(file){ 
                                                    if(file.size > 2048 * 1024) { alert('El archivo es muy grande (Máx 2MB)'); return; }
                                                    const reader = new FileReader(); 
                                                    reader.onload = (e) => { photoPreview = e.target.result; }; 
                                                    reader.readAsDataURL(file); 
                                                }">
                                    </label>
                                </div>
                                <p class="mt-6 text-xs text-center text-gray-400">PNG, SVG o JPG (Máx 2MB)</p>
                            </div>
                        </div>

                        {{-- Card Informativa Reactiva --}}
                        <div class="bg-indigo-600 p-6 rounded-2xl shadow-xl shadow-indigo-100 text-white relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 opacity-10">
                                <x-heroicon-s-globe-alt class="w-24 h-24" />
                            </div>

                            <h4 class="text-xs font-bold uppercase tracking-widest mb-4 opacity-80">Parámetros Regionales</h4>
                            <div class="space-y-4 relative z-10">
                                <div class="flex justify-between items-center border-b border-indigo-500/50 pb-2">
                                    <span class="text-sm opacity-90">Moneda</span>
                                    <span class="font-mono font-bold text-lg" x-text="`${currency} (${symbol})`"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm opacity-90">Zona Horaria</span>
                                    <span class="font-mono text-[10px] font-bold" x-text="timezone"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- COLUMNA DERECHA --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Datos Corporativos --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50">
                                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                                    <x-heroicon-s-building-office-2 class="w-5 h-5 text-gray-400" />
                                    Información Corporativa
                                </h2>
                            </div>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <x-input-label for="nombre_empresa" value="Nombre Legal de la Empresa" />
                                    <x-text-input id="nombre_empresa" name="nombre_empresa" type="text" class="mt-1 block w-full" 
                                        value="{{ old('nombre_empresa', $config->nombre_empresa ?? '') }}" required />
                                </div>

                                {{-- Input de Teléfono con Prefijo Dinámico --}}
                                <div>
                                    <x-input-label for="telefono" value="Teléfono" />
                                    <div class="mt-1 relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none border-r border-gray-100 pr-2 my-2">
                                            <span class="text-gray-900 font-bold text-sm" x-text="`+${phoneCode}`"></span>
                                        </div>
                                        <input type="text" id="telefono" name="telefono" 
                                            class="block w-full border-gray-200 rounded-xl focus:ring-indigo-500 pl-20"
                                            value="{{ old('telefono', $config->telefono ?? '') }}"
                                            oninput="this.value = this.value.replace(/[^0-9-]/g, '')"
                                            placeholder="000-000-0000" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="email" value="Correo Electrónico" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                                        value="{{ old('email', $config->email ?? '') }}" />
                                </div>
                            </div>
                        </div>

                        {{-- Selectores de Localización Personalizados --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                                    <x-heroicon-s-map-pin class="w-5 h-5 text-gray-400" />
                                    Localización
                                </h2>
                            </div>

                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Selector de País con búsqueda --}}
                                    <div class="relative">
                                        <x-input-label value="País" />
                                        <div class="relative mt-1">
                                            <button type="button" @click="openCountry = !openCountry" 
                                                class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-left flex justify-between items-center">
                                                <span x-text="countries.find(c => c.id == selectedCountry)?.emoji + ' ' + countries.find(c => c.id == selectedCountry)?.name"></span>
                                                <x-heroicon-s-chevron-down class="w-4 h-4 text-gray-400" />
                                            </button>
                                            <input type="hidden" name="country_id" :value="selectedCountry">

                                            <div x-show="openCountry" @click.outside="openCountry = false"
                                                @keydown.escape.window="openCountry = false"
                                                class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow-2xl border border-gray-100 max-h-64 overflow-hidden flex flex-col">
                                                <div class="p-2 border-b bg-gray-50">
                                                    <input type="text" x-model="searchCountry" placeholder="Buscar país..."
                                                        class="w-full text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
                                                </div>
                                                <div class="overflow-y-auto">
                                                    <template x-for="country in filteredCountries" :key="country.id">
                                                        <button type="button" @click="updateRegionalData(country.id)"
                                                            class="w-full text-left px-4 py-2 text-sm hover:bg-indigo-50 flex items-center gap-2"
                                                            :class="selectedCountry == country.id ? 'bg-indigo-50 text-indigo-700 font-bold' : ''">
                                                            <span x-text="country.emoji"></span>
                                                            <span x-text="country.name"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Selector de Estado --}}
                                    <div class="relative">
                                        <x-input-label value="Estado / Provincia" />
                                        <div class="relative mt-1">
                                            <button type="button" @click="openState = !openState" :disabled="isLoading || states.length === 0"
                                                class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-left flex justify-between items-center disabled:bg-gray-50 disabled:text-gray-400">
                                                <span x-text="isLoading ? 'Cargando...' : (states.find(s => s.id == selectedState)?.name || 'Seleccione un estado')"></span>
                                                <template x-if="isLoading">
                                                    <x-heroicon-s-arrow-path class="w-4 h-4 animate-spin text-indigo-600" />
                                                </template>
                                            </button>
                                            <input type="hidden" name="state_id" :value="selectedState">

                                            <div x-show="openState" @click.outside="openState = false"
                                                class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow-2xl border border-gray-100 max-h-64 overflow-hidden flex flex-col">
                                                <div class="p-2 border-b bg-gray-50">
                                                    <input type="text" x-model="searchState" placeholder="Filtrar..."
                                                        class="w-full text-sm border-gray-200 rounded-lg">
                                                </div>
                                                <div class="overflow-y-auto">
                                                    <template x-for="state in filteredStates" :key="state.id">
                                                        <button type="button" @click="selectState(state)"
                                                            class="w-full text-left px-4 py-2 text-sm hover:bg-indigo-50"
                                                            :class="selectedState == state.id ? 'bg-indigo-50 text-indigo-700 font-bold' : ''">
                                                            <span x-text="state.name"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="md:col-span-1">
                                        <x-input-label for="ciudad" value="Ciudad" />
                                        <x-text-input id="ciudad" name="ciudad" type="text" class="mt-1 block w-full" 
                                            value="{{ old('ciudad', $config->ciudad ?? '') }}" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <x-input-label for="direccion" value="Dirección Completa" />
                                        <x-text-input id="direccion" name="direccion" type="text" class="mt-1 block w-full" 
                                            value="{{ old('direccion', $config->direccion ?? '') }}" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Botón Guardar con Estado de Carga --}}
                        <div class="flex items-center justify-end gap-4 pt-4">
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 py-3 px-8 rounded-xl shadow-lg transition-all"
                                ::disabled="isLoading">
                                <template x-if="!isLoading">
                                    <div class="flex items-center">
                                        <x-heroicon-s-check class="w-5 h-5 mr-2" />
                                        <span>Guardar Configuración</span>
                                    </div>
                                </template>
                                <template x-if="isLoading">
                                    <div class="flex items-center">
                                        <x-heroicon-s-arrow-path class="w-5 h-5 mr-2 animate-spin" />
                                        <span>Actualizando...</span>
                                    </div>
                                </template>
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-config-layout>