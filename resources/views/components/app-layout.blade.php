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
    
    {{-- Corregido validación del tamaño, para mobiles cerardo y para pc abierto --}}
    <body 
        class="font-sans antialiased" 
        x-data="{
            isSidebarOpen: false,
            isHovered: false,

            updateSidebarState() {
                this.isSidebarOpen = window.innerWidth >= 640;
            }
        }"
        x-init="
            updateSidebarState();
            window.addEventListener('resize', () => updateSidebarState());
        "
    >
        
        {{-- ELIMINADA la clase ml-XX para que el sidebar FLOTE cuando esté colapsado. --}}
        <div class="flex min-h-screen bg-gray-100"> 
            
{{-- SIDEBAR --}}
<x-sidebar.layout>
    
    {{-- GRUPO 1: Menú Principal --}}
    <x-sidebar.group>
        <x-sidebar.item href="/dashboard" icon="heroicon-s-home">
            Dashboard
        </x-sidebar.item>

        <x-sidebar.dropdown id="clientes" icon="heroicon-s-user-group" :activeRoutes="['clientes*']">
            Clientes
            <x-slot name="submenu">
                <x-sidebar.subitem href="/admin/clients/">Lista de Clientes</x-sidebar.subitem>
                <x-sidebar.subitem href="/points-of-sale">Puntos de Venta</x-sidebar.subitem>
                <x-sidebar.subitem href="/equipments">Equipos / Activos</x-sidebar.subitem>
                <x-sidebar.subitem href="/admin/clients/tipos-negocios">Tipos de Negocio</x-sidebar.subitem>
                <x-sidebar.subitem href="/admin/clients/tipos-equipos">Tipos de Equipos</x-sidebar.subitem>
            </x-slot>
        </x-sidebar.dropdown>

        <x-sidebar.item href="/ventas" icon="heroicon-s-currency-dollar">
            Ventas
        </x-sidebar.item>

        <x-sidebar.item href="/rutas" icon="heroicon-s-map">
            Rutas
        </x-sidebar.item>
    </x-sidebar.group>

    {{-- GRUPO 2: Usuarios y Permisos --}}
    <x-sidebar.group>
        <x-sidebar.title>Usuarios</x-sidebar.title>

        <x-sidebar.item href="/admin/users" icon="heroicon-s-users">
            Usuarios
        </x-sidebar.item>

        <x-sidebar.item href="/admin/roles" icon="heroicon-s-lock-closed">
            Roles y Permisos
        </x-sidebar.item>
    </x-sidebar.group>

    {{-- GRUPO 3: Configuración --}}
    <x-sidebar.group>
        <x-sidebar.title>Configuración</x-sidebar.title>

        <x-sidebar.item href="/admin/config" icon="heroicon-s-cog-6-tooth">
            Configuración
        </x-sidebar.item>
    </x-sidebar.group>

</x-sidebar.layout>


            
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
            <div class="flex-1 flex flex-col min-w-0 transition-all duration-300 ml-0"
                :class="{
                    'sm:ml-64': isSidebarOpen,
                    'sm:ml-20': !isSidebarOpen
                }">

                {{-- HEADER / TOPBAR --}}
                <x-header />
                
                {{-- CONTENIDO VARIABLE --}}
                {{-- AÑADIDO: Se añade el margen solo en PC y lo controla x-data para compensar el sidebar. --}}
                <main class="p-6 transition-all duration-300 w-full">
                    {{ $slot }}
                </main>

            </div>
        </div>
    </body>
</html>