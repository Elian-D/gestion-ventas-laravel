<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4">
        
        <form action="{{ route('accounting.document_types.update', $item) }}" method="POST"
            class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
            @csrf
            @method('PUT')

            <x-ui.toasts />
            
            <x-form-header
                title="Editar: {{ $item->name }}"
                subtitle="Modifique la configuración del documento. Tenga cuidado al cambiar correlativos."
                :back-route="route('accounting.document_types.index')" />

            <div class="p-8 space-y-8">
                
                {{-- SECCIÓN 1: IDENTIFICACIÓN --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50 p-6 rounded-xl border border-gray-100">
                    <div class="md:col-span-2">
                        <x-input-label value="Nombre del Documento" />
                        <x-text-input name="name" class="w-full mt-1" :value="old('name', $item->name)" required />
                    </div>

                    <div>
                        <x-input-label value="Código / Sigla" />
                        <div class="mt-1 px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg font-mono text-gray-500 font-bold uppercase">
                            {{ $item->code }}
                        </div>
                    </div>

                    <div>
                        <x-input-label value="Prefijo" />
                        <x-text-input name="prefix" class="w-full mt-1 font-mono uppercase" :value="old('prefix', $item->prefix)" />
                    </div>
                </div>



                {{-- SECCIÓN 2: CONTROL Y CUENTAS --}}
                <section>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div class="md:col-span-1">
                            <div class="bg-indigo-50/50 p-6 rounded-xl border border-indigo-100 flex flex-col items-center justify-center">
                                <x-input-label value="Correlativo Actual" class="text-indigo-600 mb-1" />
                                <span class="text-4xl font-mono font-black text-indigo-700">
                                    {{ number_format($item->current_number, 0) }}
                                </span>
                                <div class="mt-2 flex items-center gap-1 text-indigo-400">
                                    <x-heroicon-s-lock-closed class="w-3 h-3" />
                                    <span class="text-[10px] font-bold uppercase">Bloqueado por el sistema</span>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-4">
                            <div>
                                <x-input-label value="Cuenta Débito" />
                                <select name="default_debit_account_id" class="w-full mt-1 border-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">Ninguna</option>
                                    @foreach($catalogs['accounts'] as $acc)
                                        <option value="{{ $acc->id }}" {{ old('default_debit_account_id', $item->default_debit_account_id) == $acc->id ? 'selected' : '' }}>
                                            {{ $acc->code }} - {{ $acc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label value="Cuenta Crédito" />
                                <select name="default_credit_account_id" class="w-full mt-1 border-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">Ninguna</option>
                                    @foreach($catalogs['accounts'] as $acc)
                                        <option value="{{ $acc->id }}" {{ old('default_credit_account_id', $item->default_credit_account_id) == $acc->id ? 'selected' : '' }}>
                                            {{ $acc->code }} - {{ $acc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active" 
                        {{ old('is_active', $item->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Estado Activo</label>
                </section>
            </div>

            <div class="p-6 bg-gray-50 flex justify-end gap-3 border-t">
                <a href="{{ route('accounting.document_types.index') }}" class="px-4 py-2 text-sm font-medium text-gray-500">Volver</a>
                <x-primary-button class="bg-indigo-600 px-8">
                    Actualizar Cambios
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>