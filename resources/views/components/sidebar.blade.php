<aside x-data="{ isHovered: false }"
       
       {{-- 1. ESCRITORIO: Manejo del Hover (Activado solo si NO está abierto y es PC) --}}
       @mouseover="!isSidebarOpen && (window.innerWidth >= 640) ? isHovered = true : null"
       @mouseleave="!isSidebarOpen && (window.innerWidth >= 640) ? isHovered = false : null"

       {{-- CLASES BASE Y TRANSICIONES --}}
       class="bg-white shadow-md border-r border-gray-100 transition-all duration-300 ease-in-out z-40"
       
       {{-- CLASES CONDICIONALES PARA EL ESTADO --}}
       :class="{ 
            // Manejo de ancho (PC: w-20 vs w-64. Móvil: solo w-64)
            'w-64': isSidebarOpen || isHovered || (window.innerWidth < 640),
            'w-20': !isSidebarOpen && !isHovered && (window.innerWidth >= 640),
            
            // Posición: Fijo en PC (z-10) para empujar el contenido; Fijo y flotante en Móvil (z-50)
            // Se usa hidden en móvil para que el transition funcione correctamente
            'fixed inset-y-0 left-0 z-10': (window.innerWidth >= 640), 
            'sm:block': true, // Visible por defecto en PC

            // ** EFECTO FLOTANTE PC ** (Prioridad Z-Index y Sombra en hover)
            'z-50 shadow-2xl': isHovered,
            
            // MOVIL: Control de TRANSICIÓN LATERAL (flotante, sin empujar)
            'fixed inset-y-0 left-0 w-64 z-50': (window.innerWidth < 640), // Fijo y alto z-index en móvil
            'transform translate-x-0': isSidebarOpen && (window.innerWidth < 640), 
            '-translate-x-full': !isSidebarOpen && (window.innerWidth < 640)
       }"
       
       {{-- VISIBILIDAD MÓVIL/PC --}}
       x-show="isSidebarOpen || (window.innerWidth >= 640)"
       
>
    
    <div class="flex p-5 items-center justify-start gap-3 border-b border-gray-100">
        <x-fas-chart-line class="w-5 h-5 fill-current text-indigo-500 flex-shrink-0" /> 
        <a href="/dashboard" class="text-lg font-bold text-gray-800 tracking-tight whitespace-nowrap overflow-hidden" 
           {{-- Muestra el texto si está abierto por click O por hover --}}
           x-show="isSidebarOpen || isHovered"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 translate-x-10"
           x-transition:enter-end="opacity-100 translate-x-0">
            Gestión <span class="text-indigo-600">Ventas</span>
        </a>
    </div>

    <nav class="space-y-1.5 p-4">
        
        <a href="/dashboard" class="group">
            <span class="flex items-center gap-3 p-2 rounded-lg text-gray-600 font-medium text-sm 
                         hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-300">
                <x-heroicon-s-home class="w-5 h-5 text-gray-400 group-hover:text-indigo-500 transition-colors flex-shrink-0" />
                {{-- Muestra el texto si está abierto por click O por hover --}}
                <span :class="{ 'opacity-100': isSidebarOpen || isHovered, 'opacity-0': !isSidebarOpen && !isHovered }" 
                      class="overflow-hidden whitespace-nowrap transition-opacity duration-300">Dashboard</span>
            </span>
        </a>

        <hr class="my-2 border-gray-100">

        <div x-data="{ open: false }" class="w-full">
            {{-- El submenú solo se puede abrir/cerrar si el sidebar está completamente expandido (click o hover) --}}
            <button @click="if (isSidebarOpen || isHovered) open = !open" 
                    :class="{'bg-indigo-50 text-indigo-600': open, 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600': !open}"
                    class="flex items-center justify-between gap-2 p-2 rounded-lg w-full font-medium text-sm transition-all duration-300">
                
                <div class="flex items-center gap-3">
                    <x-heroicon-s-user-group :class="{'text-indigo-500': open, 'text-gray-400': !open}" class="w-5 h-5 transition-colors flex-shrink-0" />
                    {{-- Muestra el texto si está abierto por click O por hover --}}
                    <span :class="{ 'opacity-100': isSidebarOpen || isHovered, 'opacity-0': !isSidebarOpen && !isHovered }" 
                          class="overflow-hidden whitespace-nowrap transition-opacity duration-300">Clientes</span>
                </div>

                {{-- Muestra la flecha si está abierto por click O por hover --}}
                <svg x-show="isSidebarOpen || isHovered"
                     :class="{ 'rotate-90': open }" 
                     class="w-4 h-4 transition-transform duration-300 text-gray-400 flex-shrink-0" 
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>

            </button>

            {{-- El submenú solo se despliega si está abierto por click O por hover --}}
            <div x-show="open && (isSidebarOpen || isHovered)" 
                 class="overflow-hidden transition-all duration-300 ease-in-out" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 max-h-0"
                 x-transition:enter-end="opacity-100 max-h-screen"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 max-h-screen"
                 x-transition:leave-end="opacity-0 max-h-0">
                
                <div class="py-1 pl-10 pr-2 space-y-0.5">
                    <a href="/clientes/crear" class="block px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">Crear Nuevo</a>
                    <a href="/clientes" class="block px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">Listar Clientes</a>
                    <a href="/clientes/editar" class="block px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">Gestionar</a>
                    <a href="/clientes/papelera" class="block px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">Papelera</a>
                </div>
            </div>
        </div>
        
        {{-- ... Repetir la lógica para Rutas, Ventas y Usuarios ... --}}
        
        <div x-data="{ open: false }" class="w-full">
            <button @click="if (isSidebarOpen || isHovered) open = !open" 
                    :class="{'bg-indigo-50 text-indigo-600': open, 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600': !open}"
                    class="flex items-center justify-between gap-2 p-2 rounded-lg w-full font-medium text-sm transition-all duration-300">
                
                <div class="flex items-center gap-3">
                    <x-heroicon-s-map :class="{'text-indigo-500': open, 'text-gray-400': !open}" class="w-5 h-5 transition-colors flex-shrink-0" />
                    <span :class="{ 'opacity-100': isSidebarOpen || isHovered, 'opacity-0': !isSidebarOpen && !isHovered }" 
                          class="overflow-hidden whitespace-nowrap transition-opacity duration-300">Rutas</span>
                </div>

                <svg x-show="isSidebarOpen || isHovered" :class="{ 'rotate-90': open }" class="w-4 h-4 transition-transform duration-300 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>

            </button>
            <div x-show="open && (isSidebarOpen || isHovered)" class="overflow-hidden transition-all duration-300 ease-in-out" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen" 
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0">
                <div class="py-1 pl-10 pr-2 space-y-0.5">
                    <a href="/rutas/crear" class="block px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">Crear Ruta</a>
                    <a href="/rutas" class="block px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">Ver Rutas</a>
                    <a href="/rutas/asignar" class="block px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">Asignar</a>
                </div>
            </div>
        </div>

        <div x-data="{ open: false }" class="w-full">
            <button @click="if (isSidebarOpen || isHovered) open = !open" 
                    :class="{'bg-indigo-50 text-indigo-600': open, 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600': !open}"
                    class="flex items-center justify-between gap-2 p-2 rounded-lg w-full font-medium text-sm transition-all duration-300">
                
                <div class="flex items-center gap-3">
                    <x-heroicon-s-shopping-bag :class="{'text-indigo-500': open, 'text-gray-400': !open}" class="w-5 h-5 transition-colors flex-shrink-0" />
                    <span :class="{ 'opacity-100': isSidebarOpen || isHovered, 'opacity-0': !isSidebarOpen && !isHovered }" 
                          class="overflow-hidden whitespace-nowrap transition-opacity duration-300">Ventas</span>
                </div>

                <svg x-show="isSidebarOpen || isHovered" :class="{ 'rotate-90': open }" class="w-4 h-4 transition-transform duration-300 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>

            </button>
            <div x-show="open && (isSidebarOpen || isHovered)" class="overflow-hidden transition-all duration-300 ease-in-out" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen" 
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0">
                
                <div class="py-1 pl-10 pr-2 space-y-0.5">
                    <a href="/ventas/crear" class="block px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">Nueva Venta</a>
                    <a href="/ventas" class="block px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">Historial</a>
                    <a href="/ventas/pendientes" class="block px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">Pendientes</a>
                </div>
            </div>
        </div>
        
{{-- ... (Otros módulos como Ventas o Rutas) ... --}}
        
        <div x-data="{ open: false }" class="w-full">
            {{-- BOTÓN PRINCIPAL: USUARIOS --}}
            <button @click="if (isSidebarOpen || isHovered) open = !open" 
                    :class="{'bg-indigo-50 text-indigo-600': open, 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600': !open}"
                    class="flex items-center justify-between gap-2 p-2 rounded-lg w-full font-medium text-sm transition-all duration-300">
                
                <div class="flex items-center gap-3">
                    <x-heroicon-s-users :class="{'text-indigo-500': open, 'text-gray-400': !open}" class="w-5 h-5 transition-colors flex-shrink-0" />
                    <span :class="{ 'opacity-100': isSidebarOpen || isHovered, 'opacity-0': !isSidebarOpen && !isHovered }" 
                          class="overflow-hidden whitespace-nowrap transition-opacity duration-300">Usuarios</span>
                </div>

                <svg x-show="isSidebarOpen || isHovered" :class="{ 'rotate-90': open }" class="w-4 h-4 transition-transform duration-300 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>

            </button>
            
            {{-- CONTENIDO NIVEL 1 --}}
            <div x-show="open && (isSidebarOpen || isHovered)" class="overflow-hidden transition-all duration-300 ease-in-out" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen" 
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0">
                
                <div class="py-1 pl-10 pr-2 space-y-0.5">
                    
                    {{-- Enlaces Directos del Nivel 1 (menos enlaces) --}}
                    <a href="/usuarios/crear" class="block px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">Crear Usuario</a>
                    <a href="/usuarios/reporte" class="block px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 rounded transition">Reporte de Actividad</a>

                    {{-- DROPDOWN ANIDADO (NIVEL 2) para Roles y Permisos --}}
                    <div x-data="{ subOpen: false }" class="w-full">
                        
                        <button @click="subOpen = !subOpen" 
                                :class="{'bg-gray-100 text-indigo-600': subOpen, 'text-gray-700 hover:bg-gray-100': !subOpen}"
                                class="flex items-center justify-between px-3 py-1.5 w-full text-xs font-medium rounded transition">
                            
                            <span>
                                Seguridad y Accesos
                            </span>

                            <svg :class="{ 'rotate-90': subOpen }" class="w-3 h-3 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        
                        {{-- CONTENIDO NIVEL 2 --}}
                        <div x-show="subOpen" class="overflow-hidden"
                            x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen" 
                            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0">
                            
                            <div class="py-1 pl-4 pr-1 space-y-0.5">
                                {{-- Enlaces del Nivel 2 --}}
                                <a href="/roles" class="block pl-6 py-1 text-xs text-gray-600 hover:bg-gray-50 rounded transition">Roles</a>
                                <a href="/permisos" class="block pl-6 py-1 text-xs text-gray-600 hover:bg-gray-50 rounded transition">Permisos</a>
                            </div>
                        </div>
                        
                    </div>
                    {{-- FIN: DROPDOWN ANIDADO --}}

                </div>
            </div>
        </div>

    </nav>
</aside>