<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="w-full min-h-screen flex flex-row sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="flex flex-col justify-center items-center gap-4 w-2/5 p-10 h-screen bg-custom-gradient-1 ">
                <div class="w-fit rounded-xl bg-custom-gradient-2">
                    <a href="/">
                        <x-application-logo class="w-16 h-16 fill-current text-white" />
                    </a>
                </div>
                <h2 class="font-sans font-black text-2xl text-white">Gestión Ventas</h2>
                <p class="text-center text-blue-100">Administra ventas, clientes y rutas en un solo panel, simple y seguro.</p>
                <div>
                    <span>
                        <svg class="text-white w-12 h-12">
                            <use xlink:href="#check-circle"></use>
                        </svg>
                    </span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            
            <div class="P-6 flex flex-col items-center justify-center w-3/5">
                <div class="w-3/5 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>


