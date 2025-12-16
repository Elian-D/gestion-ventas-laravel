<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Configuración | {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased"
    x-data="{
        isSidebarOpen: false,
        updateSidebarState() {
            this.isSidebarOpen = window.innerWidth >= 640;
        }
    }"
    x-init="
        updateSidebarState();
        window.addEventListener('resize', () => updateSidebarState());
    "
>
<div class="flex min-h-screen bg-gray-100">

    {{-- SIDEBAR CONFIGURACIÓN --}}
    <x-sidebar.layout>

        <x-sidebar.group>
            <x-sidebar.title>Configuración</x-sidebar.title>

            <x-sidebar.item href="{{ route('configuration.index') }}" icon="heroicon-s-cog-6-tooth">
                Panel de configuración
            </x-sidebar.item>

            <x-sidebar.item href="{{ route('configuration.documentos.index') }}" icon="heroicon-s-identification">
                Tipos de documentos
            </x-sidebar.item>
            
            <x-sidebar.item href="{{ route('configuration.estados.index') }}" icon="heroicon-s-user">
                Estados de clientes
            </x-sidebar.item>

            <x-sidebar.item href="{{ route('configuration.dias.index') }}" icon="heroicon-s-calendar-days">
                Días de semana
            </x-sidebar.item>

            {{-- FUTUROS --}}
            {{-- <x-sidebar.item href="#">Estados de clientes</x-sidebar.item> --}}
            {{-- <x-sidebar.item href="#">Días de semana</x-sidebar.item> --}}
        </x-sidebar.group>

        <x-sidebar.group>
            <x-sidebar.item href="/dashboard" icon="heroicon-s-arrow-left">
                Volver al sistema
            </x-sidebar.item>
        </x-sidebar.group>

    </x-sidebar.layout>

    {{-- CONTENIDO --}}
    <div class="flex-1 flex flex-col transition-all duration-300 ml-0"
        :class="{
            'sm:ml-64': isSidebarOpen,
            'sm:ml-20': !isSidebarOpen
        }">

        <x-header />

        <main class="p-6">
            {{ $slot }}
        </main>

    </div>
</div>
</body>
</html>
