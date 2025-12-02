<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    {{-- AÑADIDO: isHovered. isSidebarOpen: true por defecto (tu valor original). --}}
    <body class="font-sans antialiased" x-data="{ isSidebarOpen: true, isHovered: false }"> 
        
        {{-- ELIMINADA la clase ml-XX para que el sidebar FLOTE cuando esté colapsado. --}}
        <div class="flex min-h-screen bg-gray-100"> 
            
            {{-- SIDEBAR --}}
            {{-- CORREGIDO: ELIMINADAS las propiedades :is-sidebar-open y :is-hovered --}}
            <x-sidebar /> 
            
            {{-- OVERLAY (Fondo oscuro para móviles) --}}
            {{-- Se activa si el sidebar está abierto Y es móvil (ancho < 640px) --}}
            <div x-show="isSidebarOpen && (window.innerWidth < 640)" 
                 @click="isSidebarOpen = false" 
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="transition ease-in duration-300" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-black bg-opacity-50 z-40 sm:hidden">
            </div>

            {{-- CONTENIDO PRINCIPAL --}}
            <div class="flex-1 flex flex-col"
            :class="{ 'sm:ml-64': isSidebarOpen, 'sm:ml-20': !isSidebarOpen }">

                {{-- HEADER / TOPBAR --}}
                <x-header />
                
                {{-- CONTENIDO VARIABLE --}}
                {{-- AÑADIDO: Se añade el margen solo en PC y lo controla x-data para compensar el sidebar. --}}
                <main class="p-6 transition-all duration-300">
                    {{ $slot }}
                </main>

            </div>
        </div>
    </body>
</html>