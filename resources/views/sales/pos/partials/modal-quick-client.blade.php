{{-- MODAL CREAR CLIENTE RÁPIDO (POS) --}}
<x-modal name="quick-create-client" maxWidth="md">
    <x-form-header 
        title="Nuevo Cliente Express" 
        subtitle="Complete los datos básicos para facturar de inmediato." />

    {{-- Alpine.js para manejar lógica de tax_id y envío AJAX --}}
    <div x-data="{ 
            loading: false,
            name: '',
            tax_id: '',
            tax_identifier_type_id: '', 
            phone: '',
            address: '',
            
            get docTypeLabel() {
                const len = this.tax_id.replace(/\\D/g, '').length;
                if (len === 9) { 
                    this.tax_identifier_type_id = 198; 
                    return 'RNC Detectado'; 
                }
                if (len === 11) { 
                    this.tax_identifier_type_id = 197; 
                    return 'Cédula Detectada'; 
                }
                this.tax_identifier_type_id = '';
                return 'Documento (Opcional)';
            },

            async submitForm() {
                if (!this.name) {
                    Swal.fire('Error', 'El nombre del cliente es obligatorio', 'error');
                    return;
                }

                this.loading = true;
                
                try {
                    const response = await fetch('{{ route('sales.pos.quick-customer.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            name: this.name,
                            tax_id: this.tax_id || null,
                            tax_identifier_type_id: this.tax_identifier_type_id || null,
                            phone: this.phone || null,
                            address: this.address || null
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Error al crear el cliente');
                    }

                    if (data.success) {
                        // Disparar evento para el POS
                        window.dispatchEvent(new CustomEvent('pos-client-created', { 
                            detail: data.client 
                        }));
                        
                        // Limpiar y Cerrar
                        this.reset();
                        this.$dispatch('close');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Cliente creado',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(data.message || 'Error en la validación');
                    }
                } catch (error) {
                    console.error('Error creating client:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Ocurrió un error al crear el cliente'
                    });
                } finally {
                    this.loading = false;
                }
            },

            reset() {
                this.name = ''; 
                this.tax_id = ''; 
                this.tax_identifier_type_id = '';
                this.phone = ''; 
                this.address = '';
            }
        }" class="p-6">

        <form @submit.prevent="submitForm()" class="space-y-4">
            {{-- 1. Nombre Completo --}}
            <div>
                <x-input-label for="q-name" value="Nombre del Cliente / Razón Social" />
                <x-text-input 
                    id="q-name" 
                    x-model="name" 
                    class="mt-1 block w-full bg-gray-50 focus:bg-white" 
                    placeholder="Ej: Juan Pérez o Empresa S.A.S" 
                    required 
                    autofocus />
            </div>

            {{-- 2. Documento con Label Inteligente --}}
            <div>
                <div class="flex justify-between items-center">
                    <x-input-label for="q-tax" x-text="docTypeLabel" />
                    <span class="text-[10px] font-bold text-indigo-600" 
                          x-show="tax_identifier_type_id" 
                          x-transition.opacity.duration.300ms
                          x-cloak>
                        AUTO-DETECTADO
                    </span>
                </div>
                <x-text-input 
                    id="q-tax" 
                    x-model="tax_id" 
                    class="mt-1 block w-full" 
                    placeholder="00100000000"
                    maxlength="11" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- 3. Teléfono --}}
                <div>
                    <x-input-label for="q-phone" value="Teléfono" />
                    <x-text-input 
                        id="q-phone" 
                        x-model="phone" 
                        class="mt-1 block w-full" 
                        placeholder="809-000-0000"
                        type="tel" />
                </div>
                
                {{-- 4. Ubicación Helper --}}
                <div class="opacity-60">
                    <x-input-label value="Ubicación Base" />
                    <div class="mt-2 text-xs text-gray-500">
                        <svg class="w-3 h-3 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        {{ general_config()->ciudad }}, {{ general_config()->state->name ?? 'Configurada' }}
                    </div>
                </div>
            </div>

            {{-- 5. Dirección --}}
            <div>
                <x-input-label for="q-address" value="Dirección Corta" />
                <x-text-input 
                    id="q-address" 
                    x-model="address" 
                    class="mt-1 block w-full text-sm" 
                    placeholder="Calle, No., Sector..." />
            </div>

            {{-- Botones --}}
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button 
                    type="button"
                    @click="$dispatch('close')" 
                    x-bind:disabled="loading">
                    Cancelar
                </x-secondary-button>
                
                <button
                    type="submit"
                    x-bind:disabled="loading || !name"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                    
                    <span x-show="!loading">Registrar y Seleccionar</span>
                    
                    <span x-show="loading" class="flex items-center" x-cloak>
                        <svg class="animate-spin h-4 w-4 mr-2 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Procesando...
                    </span>
                </button>
            </div>
        </form>
    </div>
</x-modal>