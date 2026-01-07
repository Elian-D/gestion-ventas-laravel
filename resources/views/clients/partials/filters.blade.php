<div x-data="{ open: false }" class="mb-4">
    <form id="clients-filters" method="GET">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-3">
            
            <div class="relative flex-grow w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Buscar cliente por nombre o teléfono..." 
                    class="w-full border rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition shadow-sm">
            </div>

            <div class="relative w-full md:w-auto text-left">
                <button @click="open = !open" type="button"
                    class="inline-flex justify-center w-full md:w-auto px-4 py-2 border rounded-lg bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition">
                    <svg class="mr-2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filtros
                    <svg class="ml-2 -mr-1 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open" 
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    class="origin-top-right absolute right-0 mt-2 w-72 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 p-4">
                    
                    <div class="flex flex-col gap-4">

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Estado Operativo
                            </label>

                            <select name="active" class="w-full border rounded-md px-3 py-2 text-sm focus:ring-indigo-500">
                                <option value="">Todos</option>
                                <option value="1" @selected(request('active') === '1')>Activos</option>
                                <option value="0" @selected(request('active') === '0')>Inactivos</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Estado del Cliente</label>
                            <select name="estado_cliente" class="w-full border rounded-md px-3 py-2 text-sm focus:ring-indigo-500">
                                <option value="">Todos los estados</option>
                                @foreach($estadosClientes as $estado)
                                    <option value="{{ $estado->id }}" @selected(request('estado_cliente') == $estado->id)>
                                        {{ $estado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tipo de Negocio</label>
                            <select name="business_type" class="w-full border rounded-md px-3 py-2 text-sm focus:ring-indigo-500">
                                <option value="">Todos los tipos</option>
                                @foreach($tiposNegocio as $tipo)
                                    <option value="{{ $tipo->id }}" @selected(request('business_type') == $tipo->id)>
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- CHIPS ACTIVOS (FUNCIONALES) --}}
    <div id="active-filters" class="flex flex-wrap items-center gap-2 mt-4"></div>

</div>