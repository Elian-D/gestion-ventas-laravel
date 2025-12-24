<x-config-layout>
    <div class="min-h-screen bg-slate-50 py-12 px-4"
        x-cloak
        x-data="{ 
            countries: {{ $countries->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'emoji' => $c->emoji, 'phonecode' => $c->phonecode])->toJson() }},
            states: {{ $states->toJson() }},
            taxTypes: {{ $taxTypes->toJson() }},
            
            searchCountry: '',
            searchState: '',
            openCountry: false,
            openState: false,

            selectedCountry: '{{ old('country_id', $config->country_id ?? 62) }}',
            selectedState: '{{ old('state_id', $config->state_id ?? '') }}',
            selectedTaxType: '{{ old('tax_identifier_type_id', $config->tax_identifier_type_id ?? '') }}',
            
            logoPreview: '{{ $config?->logo ? asset('storage/'.$config->logo) : '' }}',
            phoneCode: '{{ $countries->where('id', old('country_id', $config->country_id ?? 62))->first()->phonecode ?? '' }}',
            currency: '{{ old('currency', $config->currency ?? '---') }}',
            timezone: '{{ old('timezone', $config->timezone ?? 'UTC') }}',
            isLoading: false,

            updateLogoPreview(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => { this.logoPreview = e.target.result; };
                    reader.readAsDataURL(file);
                }
            },

            formatSearch(text) {
                if (!text) return '';
                return text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            },

            get filteredCountries() {
                let s = this.formatSearch(this.searchCountry);
                return this.countries.filter(c => this.formatSearch(c.name).includes(s));
            },

            get filteredStates() {
                let s = this.formatSearch(this.searchState);
                return this.states.filter(st => this.formatSearch(st.name).includes(s));
            },

            async updateRegionalData(id) {
                this.selectedCountry = id;
                this.openCountry = false;
                this.searchCountry = '';
                this.isLoading = true;
                
                try {
                    const response = await fetch(`/api/countries/${id}`);
                    const data = await response.json();
                    this.states = data.states;
                    this.taxTypes = data.tax_types;
                    this.currency = data.currency;
                    this.timezone = data.timezone;
                    this.phoneCode = data.phonecode;
                    this.selectedState = ''; 
                    this.selectedTaxType = '';
                    this.searchState = '';
                } catch (error) { console.error(error); } 
                finally { this.isLoading = false; }
            }
        }">

        <div class="max-w-4xl mx-auto">
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.out.opacity.duration.1500ms x-init="setTimeout(() => show = false, 4000)"
                    class="mb-6 flex items-center p-4 bg-emerald-50 border border-emerald-100 rounded-2xl shadow-sm">
                    <div class="flex-none w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg shadow-emerald-200">
                        <x-heroicon-s-check class="w-6 h-6 text-white" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-bold text-emerald-900">Configuración actualizada</p>
                        <p class="text-xs text-emerald-600 font-medium">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600 transition-colors">
                        <x-heroicon-s-x-mark class="w-5 h-5" />
                    </button>
                </div>
            @endif
            <form method="POST" action="{{ route('configuration.general.update') }}" enctype="multipart/form-data" class="space-y-10">
                @csrf
                @method('PUT')

                <section class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-visible relative">
                    <div x-show="isLoading" class="absolute inset-0 bg-white/50 backdrop-blur-[1px] z-10 rounded-3xl flex items-center justify-center">
                        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm border border-slate-100">
                            <div class="w-4 h-4 border-2 border-indigo-600/20 border-t-indigo-600 rounded-full animate-spin"></div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Cargando datos...</span>
                        </div>
                    </div>

                    <div class="p-6 border-b border-slate-100 flex items-center gap-4">
                        <span class="flex-none w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold shadow-lg shadow-indigo-100">1</span>
                        <div>
                            <h2 class="font-bold text-slate-800 text-xl">Ubicación de Operación</h2>
                            <p class="text-sm text-slate-500">Define la región base para el cálculo de impuestos y formatos.</p>
                        </div>
                    </div>

                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative">
                                <label class="text-xs font-bold text-slate-400 uppercase mb-2 block tracking-wider">País</label>
                                <button type="button" @click="openCountry = !openCountry" 
                                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-left flex justify-between items-center hover:border-indigo-400 transition-all">
                                    <span class="flex items-center gap-3 font-semibold text-slate-700">
                                        <span x-text="countries.find(c => c.id == selectedCountry)?.emoji"></span>
                                        <span x-text="countries.find(c => c.id == selectedCountry)?.name"></span>
                                    </span>
                                    <x-heroicon-s-chevron-down class="w-5 h-5 text-slate-400" />
                                </button>
                                <input type="hidden" name="country_id" :value="selectedCountry">
                                
                                <div x-show="openCountry" @click.outside="openCountry = false" class="absolute z-[100] mt-2 w-full bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
                                    <div class="p-3 bg-slate-50 border-b">
                                        <input type="text" x-model="searchCountry" placeholder="Buscar país..." class="w-full border-none bg-transparent text-sm focus:ring-0">
                                    </div>
                                    <div class="max-h-60 overflow-y-auto">
                                        <template x-for="country in filteredCountries" :key="country.id">
                                            <button type="button" @click="updateRegionalData(country.id)" class="w-full text-left px-5 py-3 text-sm hover:bg-indigo-50 flex items-center gap-3">
                                                <span x-text="country.emoji"></span>
                                                <span x-text="country.name"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="relative">
                                <label class="text-xs font-bold text-slate-400 uppercase mb-2 block tracking-wider">Estado / Provincia</label>
                                <button type="button" @click="openState = !openState" :disabled="isLoading"
                                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-left flex justify-between items-center disabled:opacity-50 transition-all hover:border-indigo-400">
                                    <span class="font-semibold text-slate-700 text-sm" x-text="isLoading ? 'Cargando...' : (states.find(s => s.id == selectedState)?.name || 'Seleccionar...')"></span>
                                    <x-heroicon-s-chevron-down class="w-5 h-5 text-slate-400" />
                                </button>
                                <input type="hidden" name="state_id" :value="selectedState">

                                <div x-show="openState" @click.outside="openState = false" class="absolute z-[100] mt-2 w-full bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
                                    <div class="p-3 bg-slate-50 border-b">
                                        <input type="text" x-model="searchState" placeholder="Buscar provincia..." class="w-full border-none bg-transparent text-sm focus:ring-0">
                                    </div>
                                    <div class="max-h-60 overflow-y-auto">
                                        <template x-for="state in filteredStates" :key="state.id">
                                            <button type="button" @click="selectedState = state.id; openState = false; searchState = ''" class="w-full text-left px-5 py-3 text-sm hover:bg-indigo-50">
                                                <span x-text="state.name"></span>
                                            </button>
                                        </template>
                                        <div x-show="filteredStates.length === 0" class="p-4 text-center text-xs text-slate-400 italic">No se encontraron resultados</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Ciudad</label>
                                <x-text-input name="ciudad" type="text" class="w-full" placeholder="Ej: Bonao" value="{{ $config->ciudad ?? '' }}" ::disabled="isLoading" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Dirección</label>
                                <x-text-input name="direccion" type="text" class="w-full" placeholder="Calle, edificio, apto..." value="{{ $config->direccion ?? '' }}" ::disabled="isLoading" />
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100" :class="isLoading ? 'opacity-50' : ''">
                            <div class="flex items-center gap-2 mb-4">
                                <x-heroicon-s-sparkles class="w-5 h-5 text-indigo-500" />
                                <h4 class="text-sm font-bold text-slate-700">Ajustes regionales aplicados</h4>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4 transition-all hover:bg-blue-50">
                                    <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-1">Moneda Local</p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-blue-700" x-text="currency"></span>
                                        <span class="text-xs text-blue-500 font-medium">Auto-detectada</span>
                                    </div>
                                </div>
    
                                <div class="bg-indigo-50/50 border border-indigo-100 rounded-2xl p-4 transition-all hover:bg-indigo-50">
                                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-1">Prefijo Telefónico</p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-indigo-700" x-text="`+${phoneCode}`"></span>
                                        <span class="text-xs text-indigo-500 font-medium">Internacional</span>
                                    </div>
                                </div>
    
                                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 transition-all">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Zona Horaria</p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-slate-700 truncate" x-text="timezone"></span>
                                        <x-heroicon-s-clock class="w-4 h-4 text-slate-400" />
                                    </div>
                                </div>
                            </div>
                            
                            <p class="mt-3 text-[11px] text-slate-400 italic">
                                * Estos valores se sincronizan automáticamente con el país y estado seleccionados para garantizar la validez fiscal.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="bg-white rounded-3xl shadow-sm border border-slate-200">
                    <div class="p-6 border-b border-slate-100 flex items-center gap-4">
                        <span class="flex-none w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">2</span>
                        <h2 class="font-bold text-slate-800 text-lg">Canales de Contacto</h2>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase mb-2 block tracking-wider">Correo Electrónico Corporativo</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <x-heroicon-s-envelope class="w-5 h-5 text-slate-400" />
                                </div>
                                <x-text-input name="email" type="email" class="w-full pl-11" placeholder="admin@empresa.com" value="{{ $config->email ?? '' }}" />
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase mb-2 block tracking-wider">Teléfono de Contacto</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-4 rounded-l-2xl border border-r-0 border-slate-200 bg-slate-50 text-slate-500 font-bold sm:text-sm" x-text="`+${phoneCode}`"></span>
                                <x-text-input name="telefono" type="text" class="flex-1 rounded-l-none" placeholder="809 000 0000" value="{{ $config->telefono ?? '' }}" />
                            </div>
                        </div>
                    </div>
                </section>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <section class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">3</span>
                            <h3 class="font-bold text-slate-800 text-xs uppercase tracking-widest">Logo de la Empresa</h3>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-32 h-32 rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden mb-4 relative group">
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" class="object-contain w-full h-full p-2">
                                </template>
                                
                                <template x-if="!logoPreview">
                                    <x-heroicon-s-photo class="w-12 h-12 text-slate-300" />
                                </template>

                                <div x-show="logoPreview" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-[10px] text-white font-bold uppercase">Cambiar</span>
                                </div>
                            </div>

                            <input type="file" 
                                name="logo" 
                                accept="image/*"
                                @change="updateLogoPreview"
                                class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                            
                            <p class="mt-2 text-[10px] text-slate-400 italic text-center">Formatos recomendados: PNG o SVG (200x200px)</p>
                        </div>
                    </section>

                    <section class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">4</span>
                            <h3 class="font-bold text-slate-800 text-xs uppercase tracking-widest">Información Legal</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase mb-1">Nombre Comercial</label>
                                <x-text-input name="nombre_empresa" class="w-full py-2" value="{{ $config->nombre_empresa ?? '' }}" />
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase mb-1">Identificación Fiscal</label>
                                <div class="flex gap-2">
                                    <select name="tax_identifier_type_id" class="w-24 border-slate-200 rounded-xl text-[10px] bg-slate-50 font-bold" x-model="selectedTaxType" :disabled="isLoading">
                                        <option value="">Tipo</option>
                                        <template x-for="type in taxTypes" :key="type.id">
                                            <option :value="type.id" x-text="type.code"></option>
                                        </template>
                                    </select>
                                    <x-text-input name="tax_id" class="flex-1 py-2" placeholder="ID Fiscal" value="{{ $config->tax_id ?? '' }}" ::disabled="isLoading" />
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="sticky bottom-6 bg-white/90 backdrop-blur border border-slate-200 p-4 rounded-3xl shadow-xl flex items-center justify-between z-[40]">
                    <x-secondary-button type="button" x-on:click="$dispatch('open-modal', 'confirm-discard')">
                        Descartar cambios
                    </x-secondary-button>

                    <x-primary-button class="bg-indigo-600 px-8 py-3 rounded-2xl" ::disabled="isLoading">
                        <span x-show="!isLoading">Guardar Cambios</span>
                        <span x-show="isLoading">Procesando...</span>
                    </x-primary-button>
                </div>
            </form>
        </div>

        <x-modal name="confirm-discard" :show="false" maxWidth="md">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <x-heroicon-s-exclamation-triangle class="w-6 h-6 text-red-600" />
                </div>
                
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg font-bold text-slate-800 leading-6">
                        ¿Descartar cambios?
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-slate-500">
                            Se perderán todos los datos modificados en este formulario y se recargarán los valores guardados en la base de datos.
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-center gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Continuar editando
                    </x-secondary-button>

                    <x-danger-button @click="window.location.reload()">
                        Sí, descartar todo
                    </x-danger-button>
                </div>
            </div>
        </x-modal>
    </div>
</x-config-layout>